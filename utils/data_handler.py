import pandas as pd
from sklearn.cluster import KMeans
from sklearn.preprocessing import scale


class DataHandler:

    @staticmethod
    def read_data_csv(path):
        data = pd.read_csv(path)
        return data

    @staticmethod
    def read_data_html(url):
        data = pd.read_html(url)
        return data

    @staticmethod
    def read_data_json(path):
        data = pd.read_json(path)
        return data

    @staticmethod
    def read_data_excel(path):
        data = pd.read_excel(path)
        return data

    @staticmethod
    def convert_column_names_to_numbers(data):
        data.rename(columns={x: y for x, y in zip(data.columns, range(0, len(data.columns)))}, inplace=True)
        return data
