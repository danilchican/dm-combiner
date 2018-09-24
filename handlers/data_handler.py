import io

import pandas as pd
import requests
import numpy as np

from utils.decorators import exception
from utils import helpers
from libs.skl import SKL


class DataHandler:
    def __init__(self, path):
        self.path = path

    @exception
    def read_data_csv(self):
        data = pd.read_csv(self.path)
        return data

    @exception
    def read_data_html(self, table_index=0):
        # pd.read_html return list of frames
        data = pd.read_html(self.path)
        # get frame from list
        data = data[table_index]
        return data

    @exception
    def read_data_excel(self):
        data = pd.read_excel(self.path)
        return data

    @exception
    def get_csv_from_url(self):
        content = requests.get(self.path).content
        data = pd.read_csv(io.StringIO(content.decode('utf-8')))
        return data

    @staticmethod
    def convert_column_names_to_numbers(data):
        data.rename(columns={x: y for x, y in zip(data.columns, range(0, len(data.columns)))}, inplace=True)
        return data

    @staticmethod
    def get_numerical_data(data):
        data = data.select_dtypes(include=[np.number])
        return data


if __name__ == '__main__':
    data_handler = DataHandler('../data/telecom_churn.csv')
    data = data_handler.read_data_csv()
    # print(data_handler.data.head(), end='\n' * 2 + '-' * 60 + '\n' * 2)
    # data_handler2 = DataHandler('http://htmlbook.ru/html/table')
    # data_handler2.read_data_html()
    # print(data_handler2.data.head(), end='\n' * 2 + '-' * 60 + '\n' * 2)
    # data_handler2 = DataHandler("https://raw.githubusercontent.com/cs109/2014_data/master/countries.csv")
    # data = data_handler2.get_csv_from_url()
    loaded_data = data_handler.convert_column_names_to_numbers(data)
    data = data_handler.get_numerical_data(loaded_data)[[1, 2, 5, 6]]
    helpers.save_features_2d('test.png', data[1], data[2])
    reducted_data = SKL.data_reduction(data)

    clusters_info_dict = SKL.k_means(data=data, n_clusters=3)
    colors = helpers.get_color_code(clusters_info_dict['labels'].astype(float), 3)
    helpers.save_clusters_2d('cluster_res_' + str(3) + '.png', reducted_data, clusters_info_dict['centers'], colors)

    print(data)
