import os

import numpy as np
from flask import request, jsonify, send_file, Blueprint

from dm_combiner.config import Config
from dm_combiner.frameworks.framework import Framework
from dm_combiner.frameworks.skl import SKL
from dm_combiner.handlers.data_handler import DataHandler
from dm_combiner.utils import helpers
from dm_combiner.utils.decorators import view_exception
from dm_combiner.utils.logger import logger
from . import tasks

api = Blueprint('api', __name__)


@api.route('/frameworks', methods=['GET'])
@view_exception
def get_frameworks():
    task = tasks.my_background_task.apply_async(args=[10, 20])
    print(task.id, task.state)
    print(task.get())
    result = []
    frameworks = Framework().get_subclasses()
    for framework_name, framework_instance in frameworks.items():
        result.append({"name": framework_name, 'methods': list(framework_instance().methods.keys())})
    return jsonify({'success': True, 'frameworks': result})


@api.route('/args/<string:framework_name>/<string:method_name>', methods=['GET'])
@view_exception
def get_args(framework_name: str, method_name: str):
    frameworks = Framework().get_subclasses()
    framework = frameworks.get(framework_name)
    if not framework:
        return jsonify({'success': False, 'error': 'No such framework'})
    args = framework().get_method_params(method_name)
    if args:
        return jsonify({'success': True, 'args': args})
    else:
        return jsonify({'success': True, 'error': 'No such method'})


@api.route('/files', methods=['GET'])
@view_exception
def get_files_list():
    files = []
    for file in os.listdir(Config().STATIC_FILES):
        if os.path.isfile(os.path.join(Config().STATIC_FILES, file)):
            files.append(file)
    return jsonify({'success': True, 'files': files})


@api.route('/get_file/<string:filename>', methods=['GET'])
@view_exception
def get_file(filename: str):
    file_path = os.path.join(Config().STATIC_FILES, filename)
    if os.path.isfile(file_path):
        return send_file(os.path.join(Config().STATIC_FILES, filename), attachment_filename=filename)
    else:
        return jsonify({'success': False, 'error': 'No such file'})


@api.route('/get_file_path/<string:filename>', methods=['GET'])
@view_exception
def get_file_path(filename: str):
    file_path = os.path.join(Config().STATIC_FILES, filename)
    if os.path.isfile(file_path):
        return jsonify({'success': True, 'path': file_path})
    else:
        return jsonify({'success': False, 'error': 'No such file'})


@api.route('/upload_file', methods=['POST'])
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


@api.route('/algorithm', methods=['POST'])
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
    data = parse_config(config)
    data = parse_commands(data, commands)
    data = DataHandler().jsonify_data(data=data)
    if data is None:
        return jsonify({'success': False, 'error': 'Bad configs'})
    return jsonify({'success': True, 'result': data})


def parse_commands(data: np.ndarray, commands: list):
    for command in commands:
        framework_name = command.get('framework')
        frameworks = Framework().get_subclasses()
        framework_class = frameworks.get(framework_name)
        if framework_class:
            method_name = command.get('name')
            args = command.get('params')
            method = getattr(framework_class(), method_name)
            print(method)
            data = method(data, **args)
            print(data)
    return data


def parse_config(config: dict):
    file_path = config.get('file_url')
    is_normalize = config.get('normalize', False)
    is_scale = config.get('scale', False)
    callback_url = config.get('callback_url')
    columns = config.get('columns')
    if not all([file_path, callback_url, columns]):
        return None
    data = prepare_data(file_path=file_path, columns=columns)
    if data is not None:
        print(f'without: {data}')
        if is_normalize:
            data = SKL().normalize(data=data)
            print(f'with norm: {data}')
        if is_scale:
            data = SKL().scale(data=data)
            print(f'with scale: {data}')
        return data


def prepare_data(file_path: str, columns: list):
    if os.path.isfile(file_path):
        data_handler = DataHandler()
        data = data_handler.process_file(path=file_path, columns=columns)
        if data is not None:
            return data
    return None


@api.route('/status/<task_id>')
def taskstatus(task_id):
    task = tasks.my_background_task.AsyncResult(task_id)
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



