import json

from flask import Flask, request, jsonify

from utils import k_means

app = Flask(__name__)


@app.route('/')
def hello_world():
    return 'Hello World!'


@app.route('/proccess_json', methods=['GET', 'POST'])
def process_json():
    if request.method == 'POST':
        params = json.loads(request.get_json(), encoding='utf-8')
        print(params)
        a = k_means.process(**params)
        print(a)
    else:
        return '<h1>Just get request</h1>'

    return jsonify({'success': True, 'centers': a.tolist()})


if __name__ == '__main__':
    app.run()
