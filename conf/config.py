import os
import sys
import yaml


PROJECT_ROOT = os.path.join(os.path.dirname(os.path.realpath(__file__)), "../")
PROJECT_ROOT = os.path.normpath(PROJECT_ROOT)
STATIC_FILES = os.path.join(PROJECT_ROOT, 'data/')

if PROJECT_ROOT not in sys.path:
    sys.path.append(PROJECT_ROOT)


class DictToClass(object):
    def __init__(self, dict_data):
        self.__dict__.update(dict_data)


def fetch_config(path=None):

    if path is None:
        path = os.path.join(PROJECT_ROOT, 'conf', 'config.yaml')

    try:
        with open(path, 'r') as f:
            return DictToClass(yaml.load(f))
    except IOError:
        print("Can't read %s" % path)
        sys.exit(1)


CONFIG = fetch_config()

if __name__ == '__main__':
    print(STATIC_FILES, PROJECT_ROOT )
