import io
import os

import numpy as np
import pandas as pd
import requests

from utils import logger
from utils.decorators import exception


class DataHandler:
    ALLOWED_EXTENSIONS = ['csv']
    FILLNA_VALUE = 0

    def process_file(self, path: str, columns: list):
        if self.is_path_exist(path):
            if self.filter_file_extension(path):
                try:
                    data = self.read_csv(path)
                    data = self.convert_column_names_to_numbers(data)
                    data = self.fillna_df(data)      # replace NAN values in frame with zeros
                    data = data[columns]             # get certain columns from frame
                    data = data.values               # convert dataframe to np.ndarray
                except Exception as ex:
                    logger.logger.warning('{}: {}'.format(type(ex).__name__, ex))
                    return None
                return data
        return None

    def filter_file_extension(self, filename):
        return '.' in filename and filename.rsplit('.', 1)[1].lower() in self.ALLOWED_EXTENSIONS

    def fillna_df(self, data):
        data = data.fillna(self.FILLNA_VALUE)
        return data

    @staticmethod
    def jsonify_data(data: dict) -> list:
        """
        Convert data to list of dicts and if dict value is np.ndarray
        then convert it to usual list.
        """
        result = []
        for key in data:
            temp_dict = dict()
            temp_dict['name'] = key
            if isinstance(data[key], np.ndarray):
                temp_dict['data'] = data[key].tolist()
            else:
                temp_dict['data'] = data[key]
            result.append(temp_dict)
        return result

    @staticmethod
    @exception
    def get_csv_from_url(path: str):
        content = requests.get(path).content
        data = pd.read_csv(io.StringIO(content.decode('utf-8')))
        return data

    @staticmethod
    @exception
    def read_csv(path: str):
        data = pd.read_csv(path)
        return data

    @staticmethod
    def is_path_exist(path: str):
        return os.path.exists(path)

    @staticmethod
    def convert_column_names_to_numbers(data):
        data = data.rename(columns={x: y for x, y in zip(data.columns, range(1, len(data.columns) + 1))})
        return data

    @staticmethod
    def get_numerical_data(data):
        data = data.select_dtypes(include=[np.number])
        return data
