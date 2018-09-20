import json
from dataclasses import dataclass, field
from typing import List

from flask import Flask, request, jsonify

app = Flask(__name__)

@dataclass
class Algorithm():
    commands: List[str] = field(default_factory=['k-means', 'load', 'save', 'normalize', 'scale', 'pca'])

class JsonHandler():

    def __init__(self, row_data):
        self.raw_data = row_data



@app.route('/proccess_json', methods=['POST'])
def process_json():
    if request.method == 'POST':
        row_data = json.loads(request.get_json(), encoding='utf-8')
        print(Algorithm().commands)
        for param in row_data:
            print(param)
        # a = k_means.process(**params)
        # print(a)

    return jsonify({'success': True})


if __name__ == '__main__':
    app.run()
