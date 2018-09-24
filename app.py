from dataclasses import dataclass, field
from typing import List

from flask import Flask, request, jsonify

from handlers.json_handler import JsonHandler
from utils.logger import logger

app = Flask(__name__)


@dataclass
class Algorithm():
    commands: List[str] = field(default_factory=lambda: [])


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

    return jsonify({'success': 'Molodec Vladik!! Ty vse sdelal pravilno!!!'})


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
