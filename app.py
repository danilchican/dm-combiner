import json
from dataclasses import dataclass, field
from typing import List

from flask import Flask, request, jsonify

from utils import logger

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

    def validate_commands(self):
        for al_step in self.json.values():
            if al_step['name'] in self.commands.keys():
                pass
            else:
                return 0
        return 1

    def validate_params(self):
        for al_step in self.json.values():
            for param in al_step['params']:
                if param in self.commands.get(al_step['name']):
                    pass
                else:
                    print('bad - {param} {command}'.format(param=param, command=(al_step['name'])))
        return 1


@app.route('/process_json', methods=['POST'])
def process_json():
    try:
        raw_data = json.loads(request.get_json(), encoding='utf-8')
    except Exception as ex:
        logger.warning('{}: {}'.format(type(ex).__name__, ex))
        return jsonify({'success': False, 'error': ex})
    json_nadler = JsonHandler(raw_data)
    print(json_nadler.validate_commands())
    print(json_nadler.validate_params())

    return jsonify({'success': True})


if __name__ == '__main__':
    app.run()
