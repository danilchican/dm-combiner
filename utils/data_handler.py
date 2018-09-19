import pandas as pd
import os
import io
import requests
from utils.logger import logger


class DataHandler:

    @staticmethod
    def read_data_csv(path):
        logger.info('Started normalize_data')

        data = pd.read_csv(path)
        return data

    @staticmethod
    def read_data_html(url):
        logger.info('Started normalize_data')

        data = pd.read_html(url)
        return data

    @staticmethod
    def read_data_excel(path):
        logger.info('Started normalize_data')

        data = pd.read_excel(path)
        return data

    @staticmethod
    def get_csv_from_url(url):
        logger.info('Started normalize_data')

        content = requests.get(url).content
        data = pd.read_csv(io.StringIO(content.decode('utf-8')))
        return data

    @staticmethod
    def convert_column_names_to_numbers(data):
        data.rename(columns={x: y for x, y in zip(data.columns, range(0, len(data.columns)))}, inplace=True)
        return data


if __name__ == '__main__':
    data = DataHandler.read_data_csv('../data/telecom_churn.csv')
    print(data, end='\n' * 2 + '-' * 60 + '\n' * 2)
    data = DataHandler.read_data_html('http://htmlbook.ru/html/table')
    print(data, end='\n' * 2 + '-' * 60 + '\n' * 2)
    print(DataHandler.get_csv_from_url(url="https://raw.githubusercontent.com/cs109/2014_data/master/countries.csv"))
