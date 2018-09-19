import io

import pandas as pd
import requests

from utils.logger import logger
from utils.decorators import exception, log_result


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
        print(data)
        return data

    @staticmethod
    def convert_column_names_to_numbers(data):
        data = data.rename(columns={x: y for x, y in zip(data.columns, range(0, len(data.columns)))}, inplace=True)
        return data


if __name__ == '__main__':
    # data_handler = DataHandler('../data/telecom_churn.csv')
    # data_handler.read_data_csv()
    # print(data_handler.data.head(), end='\n' * 2 + '-' * 60 + '\n' * 2)
    # data_handler2 = DataHandler('http://htmlbook.ru/html/table')
    # data_handler2.read_data_html()
    # print(data_handler2.data.head(), end='\n' * 2 + '-' * 60 + '\n' * 2)
    data_handler2 = DataHandler("https://raw.githubusercontent.com/cs109/2014_data/master/countries.csv")
    data = data_handler2.get_csv_from_url()
    print(data)
