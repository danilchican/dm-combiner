from flask import Flask, request, jsonify, send_file
import os

from werkzeug.utils import secure_filename

from handlers.json_handler import JsonHandler
from handlers.data_handler import DataHandler
from conf.config import PROJECT_ROOT, STATIC_FILES
from utils.logger import logger
from utils import helpers

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
    return jsonify({'success': 'true', 'result': data_dict})


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
def return_file(filename):
    try:
        print(os.path.join(PROJECT_ROOT, 'data', filename))
        return send_file(os.path.join(STATIC_FILES, filename), attachment_filename=filename)
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
        return jsonify({'success': False, 'error': "No file part."})
    file = request.files['file']
    if file.filename == '':
        return jsonify({'success': False, 'error': "No selected file."})
    if file and helpers.filter_file_extension(file.filename):
        filename = secure_filename(file.filename)
        file.save(os.path.join(STATIC_FILES, filename))
        return jsonify({'success': True})
    return jsonify({'success': False, 'error': "Not allowed format."})


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000)
