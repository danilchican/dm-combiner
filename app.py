import os

from flask import Flask, request, jsonify, send_file

from conf.config import PROJECT_ROOT, STATIC_FILES
from handlers.data_handler import DataHandler
from handlers.json_handler import JsonHandler
from frameworks.skl import SKL
from frameworks.framework import Framework
from utils import helpers
from utils.logger import logger
from utils.decorators import view_exception

app = Flask(__name__)


# --- Frameworks | commands | params
@app.route('/frameworks', methods=['GET'])
@view_exception
def frameworks():
    frameworks_dict = Framework().get_subclasses()
    frameworks_names = list(frameworks_dict.keys())
    if not frameworks_names:
        return jsonify({'success': True, 'result': 'No frameworks.'})
    return jsonify({'success': True, 'result': frameworks_names})


@app.route('/commands/<string:framework_name>', methods=['GET'])
@view_exception
def commands(framework_name):
    frameworks = Framework().get_subclasses()
    framework = frameworks.get(framework_name)
    if not framework:
        return jsonify({'success': False, 'error': 'No such framework'})
    commands = framework().methods
    commands = list(commands.keys())
    return jsonify({'success': 'true', 'result': commands})


@app.route('/params/<string:framework_name>/<string:func_name>', methods=['GET'])
@view_exception
def params(framework_name, func_name):
    frameworks = Framework().get_subclasses()
    framework = frameworks.get(framework_name)
    if not framework:
        return jsonify({'success': False, 'error': 'No such framework'})
    params = framework().methods_params.get(func_name)
    if params:
        return jsonify({'success': 'true', 'result': params})
    else:
        return jsonify({'success': 'true', 'error': 'No such command.'})


# --- Views which working with files
@app.route('/files', methods=['GET'])
@view_exception
def files():
    files = []
    for file in os.listdir(STATIC_FILES):
        if os.path.isfile(os.path.join(STATIC_FILES, file)):
            files.append(file)
    return jsonify({'success': True, 'result': files})


@app.route('/get_file/<string:filename>', methods=['GET'])
@view_exception
def get_file(filename):
    return send_file(os.path.join(STATIC_FILES, filename), attachment_filename=filename)


@app.route('/get_file_path/<string:filename>', methods=['GET'])
@view_exception
def get_file_path(filename):
    file_path = os.path.join(STATIC_FILES, filename)
    if os.path.isfile(file_path):
        return jsonify({'success': True, 'result': file_path})
    else:
        return jsonify({'success': False, 'error': 'No such file.'})


@app.route('/upload_file', methods=['POST'])
@view_exception
def upload_file():
    if 'file' not in request.files:
        logger.warn('upload_file | No file part.')
        return jsonify({'success': False, 'error': "No file part."})
    file = request.files['file']
    if file.filename == '':
        logger.warn('upload_file | No selected file.')
        return jsonify({'success': False, 'error': "No selected file."})
    if not helpers.filter_file_extension(file.filename):
        logger.warn('upload_file | No selected file.')
        return jsonify({'success': False, 'error': "Not allowed format."})
    file_path = helpers.save_file(file)
    return jsonify({'success': True, 'result': file_path})


# --- Algorithm views
@app.route('/preview', methods=['POST'])
@view_exception
def preview():
    logger.info('Received request: {method}, {url}'.format(method=request.method, url=request.host_url))
    try:
        raw_data = request.get_json(force=True)
        logger.info('Request Data | type: {type}, data: {data}'.format(type=type(raw_data), data=raw_data))
    except Exception as ex:
        logger.warning('{}: {}'.format(type(ex).__name__, ex))
        return jsonify({'success': False, 'error': str(ex)})
    json_handler = JsonHandler(raw_data)
    is_validate_success, error = json_handler.validate_json()

    if not is_validate_success:
        return jsonify({'success': False, 'error': error})
    commands = json_handler.compose_commands()
    for command, params in commands.items():
        if command == 'load':
            data_handler = DataHandler(os.path.join(PROJECT_ROOT, params.get('path')))
            data = data_handler.read_data_csv()
            data = data.head(n=10)
            data_dict = data.to_dict('list')
    data = []
    for i in data_dict:
        d ={}
        d['title'] = i
        d['data'] = data_dict[i]
        data.append(d)
    return jsonify({'success': 'true', 'result': data})


@app.route('/process_json', methods=['POST'])
@view_exception
def process_json():
    logger.info('Received request: {method}, {url}'.format(method=request.method, url=request.host_url))
    try:
        raw_data = request.get_json(force=True)
        logger.info('Request Data | type: {type}, data: {data}'.format(type=type(raw_data), data=raw_data))
    except Exception as ex:
        logger.warning('{}: {}'.format(type(ex).__name__, ex))
        return jsonify({'success': False, 'error': str(ex)})

    json_handler = JsonHandler(raw_data)
    # is_validate_success, error = json_handler.validate_json()
    #
    # if not is_validate_success:
    #     return jsonify({'success': False, 'error': error})

    commands = json_handler.compose_commands()

    data_handler = DataHandler(os.path.join(PROJECT_ROOT, commands['load'].get('path')))
    data = data_handler.read_data_csv()
    data = data_handler.get_numerical_data(data)
    # data = data.head(n=10)
    methods = SKL().methods
    for i in commands:
        if i in methods and i == 'k_means':
            result = methods[i](data, **commands[i])
            for i in result:
                result[i] = result[i].tolist()
    data_dict = data.to_dict('list')
    return jsonify({'success': 'true', 'result': result})


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000)
