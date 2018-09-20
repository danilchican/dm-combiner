import requests
import json

json_ob = {
    "command_1": {
        "name": "load",
        "framework": "scikit",

        "data_type": "DataType",
        "data_size": "размер данных для массива",
        "data": "сами данные",
    },

    "command_2": {
        "name": "kmeans",
        "framework": "scikit",

        "num_clusters": 2,
    },

    "command_3": {
        "name": "save",
        "framework": "scikit",

        "data_type": "DataType",
        "data_size": "размер данных для массива",
        "data": "сами данные",
    },
}

datas = {'file_path': 'Data/telecom_churn.csv', 'column_indexes': [5, 6, 7, 8], 'number_of_clusters': 6,
         'is_normalize': True}
url = "http://127.0.0.1:5000/proccess_json"


def send_request(url):
    print(json_ob)
    data_json = json.dumps(json_ob)
    print(data_json)
    r = requests.post(url, json=data_json)
    print("Status: {} \nRaw: {} \nData: {} \n".format(r.status_code, r.raw, r.json()))


if __name__ == '__main__':
    send_request(url)
    # print(json.dumps(datas))
