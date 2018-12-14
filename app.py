import os
import time

from celery import Celery
from flask import Flask, request, jsonify, send_file

from conf.config import STATIC_FILES
from frameworks.framework import Framework
from frameworks.skl import SKL
from handlers.data_handler import DataHandler
from handlers.json_handler import JsonHandler
from utils import helpers
from utils.decorators import view_exception
from utils.logger import logger

app = Flask(__name__)
app.config['CELERY_BROKER_URL'] = 'redis://localhost:6379/0'
app.config['CELERY_RESULT_BACKEND'] = 'redis://localhost:6379/0'

celery = Celery(app.name, broker=app.config['CELERY_BROKER_URL'])
celery.conf.update(app.config)


@app.route('/frameworks', methods=['GET'])
@view_exception
def get_frameworks():
    task = my_background_task.apply_async(args=[10, 20])
    print(task.id, task.state)
    print(task.get())
    result = []
    frameworks = Framework().get_subclasses()
    for framework_name, framework_instance in frameworks.items():
        result.append({"name": framework_name, 'methods': list(framework_instance().methods.keys())})
    return jsonify({'success': True, 'frameworks': result})


@app.route('/args/<string:framework_name>/<string:method_name>', methods=['GET'])
# @view_exception
def get_args(framework_name: str, method_name: str):
    frameworks = Framework().get_subclasses()
    framework = frameworks.get(framework_name)
    if not framework:
        return jsonify({'success': False, 'error': 'No such framework'})
    args = framework().method_params(method_name)
    if args:
        return jsonify({'success': True, 'args': args})
    else:
        return jsonify({'success': True, 'error': 'No such method'})


@app.route('/files', methods=['GET'])
@view_exception
def get_files_list():
    files = []
    for file in os.listdir(STATIC_FILES):
        if os.path.isfile(os.path.join(STATIC_FILES, file)):
            files.append(file)
    return jsonify({'success': True, 'files': files})


@app.route('/get_file/<string:filename>', methods=['GET'])
@view_exception
def get_file(filename: str):
    file_path = os.path.join(STATIC_FILES, filename)
    if os.path.isfile(file_path):
        return send_file(os.path.join(STATIC_FILES, filename), attachment_filename=filename)
    else:
        return jsonify({'success': False, 'error': 'No such file'})


@app.route('/get_file_path/<string:filename>', methods=['GET'])
@view_exception
def get_file_path(filename: str):
    file_path = os.path.join(STATIC_FILES, filename)
    if os.path.isfile(file_path):
        return jsonify({'success': True, 'path': file_path})
    else:
        return jsonify({'success': False, 'error': 'No such file'})


@app.route('/upload_file', methods=['POST'])
@view_exception
def upload_file():
    if 'file' not in request.files:
        logger.warn('upload_file | No file part.')
        return jsonify({'success': False, 'error': "No file part."})
    file = request.files['file']
    if file.filename == '':
        logger.warn('upload_file | No selected file.')
        return jsonify({'success': False, 'error': "No selected file."})
    if not DataHandler().filter_file_extension(file.filename):
        logger.warn('upload_file | No selected file.')
        return jsonify({'success': False, 'error': "Not allowed format."})
    file_path = helpers.save_file(file)
    return jsonify({'success': True, 'path': file_path})


@app.route('/algorithm', methods=['POST'])
# @view_exception
def algorithm():
    try:
        data = request.get_json(force=True)
        print(data.get('config'))
        logger.info('Request Data | type: {type}, data: {data}'.format(type=type(data), data=data))
    except Exception as ex:
        logger.warning('{}: {}'.format(type(ex).__name__, ex))
        return jsonify({'success': False, 'error': str(ex)})

    config, commands = data.get('config', {}), data.get('commands', [])
    print(commands)
    is_success = parse_config(config)
    if not is_success:
        return jsonify({'success': False, 'error': 'Bad configs'})
    return jsonify({'success': True})


def parse_config(config: dict):
    file_path = config.get('file_url')
    is_normalize = config.get('normalize', False)
    is_scale = config.get('scale', False)
    callback_url = config.get('callback_url')
    columns = config.get('columns')
    if not all([file_path, callback_url, columns]):
        return None
    if os.path.isfile(file_path):
        data_handler = DataHandler(file_path)
        data = data_handler.process_file(columns)
        print(data)
        if data is not None:
            print(data)
        return True



@app.route('/status/<task_id>')
def taskstatus(task_id):
    task = my_background_task.AsyncResult(task_id)
    if task.state == 'PENDING':
        # job did not start yet
        response = {
            'state': task.state,
            'current': 0,
            'total': 1,
            'status': 'Pending...'
        }
    elif task.state != 'FAILURE':
        response = {
            'state': task.state,
            'current': task.info.get('current', 0),
            'total': task.info.get('total', 1),
            'status': task.info.get('status', '')
        }
        if 'result' in task.info:
            response['result'] = task.info['result']
    else:
        # something went wrong in the background job
        response = {
            'state': task.state,
            'current': 1,
            'total': 1,
            'status': str(task.info),  # this is the exception raised
        }
    return jsonify(response)

@celery.task
def my_background_task(arg1, arg2):
    time.sleep(10)
    return arg1 + arg2


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, threaded=True)
