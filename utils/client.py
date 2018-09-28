import os
import requests
import json

from conf.config import PROJECT_ROOT


json_ob = {
    "command_1": {
        "name": "load",
        "framework": "scikit",

        "params": {
            "path": 'data/telecom_churn.csv'
            # "data_type": "DataType",
            # "data_size": "размер данных для массива",
            # "data": "сами данные"
        },
    },

    "command_2": {
        "name": "k-means",
        "framework": "scikit",

        "params": {
            "n_clusters": 2
            },
    },

    "command_3": {
        "name": "save",
        "framework": "scikit",

        "params": {
            "data_type": "DataType",
        },
    },
}

datas = {'file_path': 'Data/telecom_churn.csv', 'column_indexes': [5, 6, 7, 8], 'number_of_clusters': 6,
         'is_normalize': True}
url = "http://127.0.0.1:5000/process_json"


def send_request(url):
    print(json_ob)
    data_json = json.dumps(json_ob)
    print(data_json)
    r = requests.post(url, json=data_json)
    print("Status: {} \nRaw: {} \nData: {} \n".format(r.status_code, r.raw, r.json()))


if __name__ == '__main__':
    # send_request(url)
    # print(json.dumps(datas))
    os.path.join(PROJECT_ROOT, 'data', '')
