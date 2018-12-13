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


@app.route('/frameworks', methods=['GET'])
@view_exception
def get_frameworks():
    result = []
    frameworks = Framework().get_subclasses()
    for framework_name, framework_instance in frameworks.items():
        result.append({"name": framework_name, 'methods': list(framework_instance().methods.keys())})
    return jsonify({'success': True, 'frameworks': result})


@app.route('/args/<string:framework_name>/<string:method_name>', methods=['GET'])
# @view_exception
def get_args(framework_name: str, method_name: str):
    frameworks = Framework().get_subclasses()
    framework = frameworks.get(framework_name)
    if not framework:
        return jsonify({'success': False, 'error': 'No such framework'})
    args = framework().method_params(method_name)
    if args:
        return jsonify({'success': True, 'args': args})
    else:
        return jsonify({'success': True, 'error': 'No such method'})


@app.route('/files', methods=['GET'])
@view_exception
def get_files_list():
    files = []
    for file in os.listdir(STATIC_FILES):
        if os.path.isfile(os.path.join(STATIC_FILES, file)):
            files.append(file)
    return jsonify({'success': True, 'files': files})


@app.route('/get_file/<string:filename>', methods=['GET'])
@view_exception
def get_file(filename: str):
    file_path = os.path.join(STATIC_FILES, filename)
    if os.path.isfile(file_path):
        return send_file(os.path.join(STATIC_FILES, filename), attachment_filename=filename)
    else:
        return jsonify({'success': False, 'error': 'No such file'})


@app.route('/get_file_path/<string:filename>', methods=['GET'])
@view_exception
def get_file_path(filename: str):
    file_path = os.path.join(STATIC_FILES, filename)
    if os.path.isfile(file_path):
        return jsonify({'success': True, 'path': file_path})
    else:
        return jsonify({'success': False, 'error': 'No such file'})


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
    if not DataHandler().filter_file_extension(file.filename):
        logger.warn('upload_file | No selected file.')
        return jsonify({'success': False, 'error': "Not allowed format."})
    file_path = helpers.save_file(file)
    return jsonify({'success': True, 'path': file_path})


# --- Algorithm views
@app.route('/preview/<string:filename>', methods=['GET'])
@view_exception
def preview(filename):
    data_handler = DataHandler(os.path.join(STATIC_FILES, filename))
    if data_handler.filter_file_extension(filename):
        if data_handler.is_path_exist():
            data = data_handler.show_file_preview()
            if data is None:
                return jsonify({'success': False, 'error': "Error during proccessing."})
            return jsonify({'success': True, 'result': data})
        else:
            return jsonify({'success': False, 'error': "No such path"})
    else:
        return jsonify({'success': False, 'error': "Bad file extension."})


@app.route('/algorithm', methods=['POST'])
# @view_exception
def algorithm():
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
    print(commands)
    methods = SKL().methods
    for command, params in commands.items():
        print(command, params)
        if command == 'load':
            print('loading')
            data_handler = DataHandler(params.get('path'))
            data = data_handler.read_data_csv()
            data = data_handler.convert_column_names_to_numbers(data)
            data = data[params.get('columns')]
        elif command == 'save':
            print('saving')
            for i in data:
                data[i] = data[i].tolist()
        else:
            if command in methods:
                print(command + 'ing')
                data = methods[command](data, **params)
    return jsonify({'success': True, 'result': data})


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
