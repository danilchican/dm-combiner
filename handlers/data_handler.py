import io
import os

import numpy as np
import pandas as pd
import requests

from frameworks.skl import SKL
from utils import helpers, logger
from utils.decorators import exception


class DataHandler:
    ALLOWED_EXTENSIONS = ['csv']
    NUM_STRINGS_FOR_PREVIEW = 10

    def __init__(self, path):
        self.path = path

    def is_path_exist(self):
        return os.path.exists(self.path)

    def filter_file_extension(self, filename):
        return '.' in filename and filename.rsplit('.', 1)[1].lower() in self.ALLOWED_EXTENSIONS

    @exception
    def read_data_csv(self):
        data = pd.read_csv(self.path)
        return data

    def process_file(self, columns: list):
        if self.is_path_exist():
            if self.filter_file_extension(self.path):
                try:
                    data = self.read_data_csv()
                    data = self.convert_column_names_to_numbers(data)
                    data = self.fillna_df(data)
                    data = data[columns]
                except Exception as ex:
                    logger.logger.warning('{}: {}'.format(type(ex).__name__, ex))
                    return None
                return data
        return None

    @staticmethod
    def restructure_data_before_send(data_dict):
        data = []
        for i in data_dict:
            temp_dict = dict()
            temp_dict['title'] = i
            temp_dict['data'] = data_dict[i]
            data.append(temp_dict)
        return data

    @exception
    def get_csv_from_url(self):
        content = requests.get(self.path).content
        data = pd.read_csv(io.StringIO(content.decode('utf-8')))
        return data

    @staticmethod
    def convert_column_names_to_numbers(data):
        data = data.rename(columns={x: y for x, y in zip(data.columns, range(1, len(data.columns) + 1))})
        return data

    @staticmethod
    def get_numerical_data(data):
        data = data.select_dtypes(include=[np.number])
        return data

    @staticmethod
    def fillna_df(data):
        data = data.fillna(0)
        return data


if __name__ == '__main__':
    data_handler = DataHandler("/home/roma/work/dm-combiner/data/telecom_churn.csv")
    data = data_handler.read_data_csv()
    loaded_data = data_handler.convert_column_names_to_numbers(data)
    data = data_handler.get_numerical_data(loaded_data)[[1, 2, 5, 6]]
    helpers.save_features_2d('test.png', data[1], data[2])
    reducted_data = SKL.data_reduction(data)

    clusters_info_dict = SKL.k_means(data=data, n_clusters=3)
    colors = helpers.get_color_code(clusters_info_dict['labels'].astype(float), 3)
    helpers.save_clusters_2d('cluster_res_' + str(3) + '.png', reducted_data, clusters_info_dict['centers'], colors)

    print(data)
