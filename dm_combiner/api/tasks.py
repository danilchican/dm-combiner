import os

import numpy as np
import json
import requests

from dm_combiner import celery
from dm_combiner.frameworks.framework import Framework
from dm_combiner.frameworks.skl import SKL
from dm_combiner.handlers.data_handler import DataHandler


@celery.task
def run_algorithm(data):
    config, commands = data.get('config', {}), data.get('commands', [])
    file_path, columns, is_normalize, is_scale, callback_url = parse_config(config)
    data = load_data(file_path=file_path, columns=columns, is_normalize=is_normalize, is_scale=is_scale)
    data = parse_commands(data, commands)
    data = DataHandler().jsonify_data(data=data)
    if data is None:
        return None
    print(data, callback_url)
    r = requests.post(callback_url, json=json.dumps(data))
    print(r)
    return data


def parse_config(config: dict):
    file_path = config.get('file_url')
    is_normalize = config.get('normalize', False)
    is_scale = config.get('scale', False)
    callback_url = config.get('callback_url')
    columns = config.get('columns')
    if not all([file_path, callback_url, columns]):
        return None
    return file_path, columns, is_normalize, is_scale, callback_url


def load_data(file_path: str, columns: list, is_normalize: bool, is_scale: bool) -> np.ndarray:
    data_handler = DataHandler()
    data = data_handler.process_file(path=file_path, columns=columns)
    if data is not None:
        if is_normalize:
            data = SKL().normalize(data=data)
        if is_scale:
            data = SKL().scale(data=data)
    return data


def parse_commands(data: np.ndarray, commands: list):
    for command in commands:
        framework_name = command.get('framework')
        frameworks = Framework().get_subclasses()
        framework_class = frameworks.get(framework_name)
        if framework_class:
            method_name = command.get('name')                  # fetch method name
            args = command.get('params')                       # fetch method args
            method = getattr(framework_class(), method_name)   # get method instance from framework class instance
            data = method(data, **args)                        # execute method with args
    return data
