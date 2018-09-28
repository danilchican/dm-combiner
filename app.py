from flask import Flask, request, jsonify
from pandas import json
import pandas as pd
import os

from handlers.json_handler import JsonHandler
from handlers.data_handler import DataHandler
from conf.config import PROJECT_ROOT
from utils.logger import logger

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
            data_dict = data.to_dict()
    return jsonify({'success': 'true', 'result': data_dict})


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


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000)
