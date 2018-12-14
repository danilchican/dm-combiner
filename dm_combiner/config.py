import os
import sys
import yaml
from dm_combiner.utils.metaclasses import Singleton


class Config(metaclass=Singleton):
    PROJECT_ROOT = os.path.normpath(os.path.join(os.path.dirname(os.path.realpath(__file__)), "../"))
    STATIC_FILES = os.path.join(PROJECT_ROOT, 'data/')

    def __init__(self):
        self.__dict__.update(self._fetch_config())

    @staticmethod
    def _fetch_config(path: str = None) -> dict:
        if path is None:
            path = os.path.join(os.path.dirname(os.path.realpath(__file__)), 'config', 'config.yaml')
        try:
            with open(path, 'r') as f:
                return yaml.load(f)
        except IOError:
            print(f"Can't read {path}")
            sys.exit(1)

    def __str__(self):
        return f"Config({self.__dict__})"


if __name__ == '__main__':
    print(Config())
