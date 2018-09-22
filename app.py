import json
from dataclasses import dataclass, field
from typing import List

from flask import Flask, request, jsonify

from utils.logger import logger

app = Flask(__name__)


@dataclass
class Algorithm():
    commands: List[str] = field(default_factory=lambda: [])


class JsonHandler():
    commands = {
        'k-means': ['n_clusters', 'max_iter', 'init', 'random_state'],
        'load': ['path', 'data_type', 'columns'],
        'save': ['data_type'],
        'normalize': ['norm', 'axis', 'copy', 'return_norm'],
        'scale': ['axis', 'with_mean', 'with_std', 'copy'],
        'pca': ['n_components'],
    }

    def __init__(self, json):
        self.json = json

    def _validate_commands(self):
        is_commands_validating_success = True
        error = ''
        for al_step in self.json.values():
            if al_step['name'] not in self.commands.keys():
                is_commands_validating_success = False
                error = 'No such command: {command}'.format(command=al_step['name'])
        return is_commands_validating_success, error

    def _validate_params(self):
        is_params_validating_success = True
        error = ''
        for al_step in self.json.values():
            for param in al_step['params']:
                if param not in self.commands.get(al_step['name']):
                    is_params_validating_success = False
                    error = "No such param in '{command}' command: {param}".format(command=al_step['name'], param=param)
        return is_params_validating_success, error

    def validate_json(self):
        is_commands_validating_success, error = self._validate_commands()
        if not is_commands_validating_success:
            return is_commands_validating_success, error

        is_params_validating_success, error = self._validate_params()
        if not is_params_validating_success:
            return is_params_validating_success, error

        return is_params_validating_success, error


@app.route('/process_json', methods=['POST'])
def process_json():
    logger.info('Received request: {method}, {url}'.format(method=request.method, url=request.host_url ))
    try:
        json_data = request.get_json(force=True)
        raw_data = json.loads(json_data, encoding='utf-8')
    except Exception as ex:
        logger.warning('{}: {}'.format(type(ex).__name__, ex))
        return jsonify({'success': False, 'error': str(ex)})
    json_handler = JsonHandler(raw_data)
    is_validate_success, error = json_handler.validate_json()
    if not is_validate_success:
        return jsonify({'success': False, 'error': error})

    return jsonify({'success': True})


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
