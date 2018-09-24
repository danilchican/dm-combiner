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
        raw_data = json.loads(raw_data)
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
            print(data.head())

    return jsonify({'success': 'True', 'result': pd.DataFrame.to_json(data)})


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
