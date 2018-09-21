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
        'load': ['path', 'data_type'],
        'save': ['data_type', ],
        'normalize': ['norm', 'axis', 'copy', 'return_norm'],
        'scale': ['axis', 'with_mean', 'with_std', 'copy'],
        'pca': ['n_components'],
    }

    def __init__(self, raw_data):
        self.raw_data = raw_data


@app.route('/process_json', methods=['POST'])
def process_json():
    try:
        raw_data = json.loads(request.get_json(), encoding='utf-8')
    except Exception as ex:
        logger.warning('{}: {}'.format(type(ex).__name__, ex))
        return jsonify({'success': False, 'error': ex})
    for param in raw_data:
        print(param)

    return jsonify({'success': True})


if __name__ == '__main__':
    app.run()
