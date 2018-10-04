from flask import Flask, request, jsonify, send_file
import os

from werkzeug.utils import secure_filename

from handlers.json_handler import JsonHandler
from handlers.data_handler import DataHandler
from conf.config import PROJECT_ROOT, STATIC_FILES
from utils.logger import logger
from utils import helpers
from frameworks.skl import SKL

app = Flask(__name__)


@app.route('/process_json', methods=['POST'])
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
    print(data)
    methods = SKL().methods
    print(methods)
    for i in commands:
        print(i)
        if i in methods and i == 'k_means':
            print(commands[i])
            result = methods[i](data, **commands[i])
            for i in result:
                result[i] = result[i].tolist()
            print(i, result)
    data_dict = data.to_dict('list')
    return jsonify({'success': 'true', 'result': result})


@app.route('/preview', methods=['POST'])
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


@app.route('/commands', methods=['GET'])
def commands():
    commands = list(JsonHandler.commands.keys())
    return jsonify({'success': 'true', 'result': commands})


@app.route('/params/<string:name>', methods=['GET'])
def params(name):
    commands = JsonHandler.commands
    params = commands.get(name)
    if params:
        return jsonify({'success': 'true', 'result': params})
    else:
        return jsonify({'success': 'true', 'error': 'No such command.'})


@app.route('/get_file/<string:filename>', methods=['GET'])
def get_file(filename):
    try:
        return send_file(os.path.join(STATIC_FILES, filename), attachment_filename=filename)
    except Exception as ex:
        logger.warning('{}: {}'.format(type(ex).__name__, ex))
        return jsonify({'success': False, 'error': str(ex)})


@app.route('/get_file_path/<string:filename>', methods=['GET'])
def get_file_path(filename):
    try:
        file_path = os.path.join(PROJECT_ROOT, 'data', filename)
        if os.path.isfile(file_path):
            return jsonify({'success': True, 'result': file_path})
        else:
            return jsonify({'success': False, 'error': 'No such file.'})
    except Exception as ex:
        logger.warning('{}: {}'.format(type(ex).__name__, ex))
        return jsonify({'success': False, 'error': str(ex)})


@app.route('/files', methods=['GET'])
def files():
    try:
        files = []
        for file in os.listdir(STATIC_FILES):
            if os.path.isfile(os.path.join(STATIC_FILES, file)):
                files.append(file)
        return jsonify({'success': True, 'result': files})
    except Exception as ex:
        logger.warning('{}: {}'.format(type(ex).__name__, ex))
        return jsonify({'success': False, 'error': str(ex)})


@app.route('/upload_file', methods=['POST'])
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

    filename = secure_filename(file.filename)
    file_path = os.path.join(STATIC_FILES, filename)
    file.save(file_path)
    return jsonify({'success': True, 'result': file_path})


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000)
