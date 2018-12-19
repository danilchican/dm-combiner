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
    result = []
    frameworks = Framework().get_subclasses()
    for framework_name, framework_instance in frameworks.items():
        result.append({"name": framework_name, 'commands': list(framework_instance().methods.keys())})
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
    # try:
    data = request.get_json(force=True)
    task = tasks.run_algorithm.apply_async((data,))
    #     logger.info(f'Algorithm start executing | Task id: {task.id} | Data: {data}')
    # except Exception as ex:
    #     logger.warning('{}: {}'.format(type(ex).__name__, ex))
    #     return jsonify({'success': False, 'error': str(ex)})
    return jsonify({'success': True, 'task_id': task.id})


@api.route('/status/<task_id>')
def get_task_status(task_id):
    task = tasks.run_algorithm.AsyncResult(task_id)
    if task.state == 'PENDING':
        # job did not start yet
        response = {
            'state': task.state,
        }
    elif task.state != 'FAILURE':
        response = {
            'state': task.state,
        }
        if 'result' in task.info:
            response['result'] = task.info['result']
    else:
        # something went wrong in the background job
        response = {
            'state': task.state,
        }
    return jsonify(response)
