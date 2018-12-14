import os
import requests
import json

from conf.config import PROJECT_ROOT

json_ob = {
    "config": {
        "normalize": True,
        "scale": True,
        "file_url": "/home/roma/work/dm-combiner/data/telecom_churn.csv",
        "columns": [6, 7, 8, 9],
        "callback_url": "localhost:8000/"
    },
    "commands": [
        {
            "name": "normalize",
            "framework": "SKL",
            "params": {
                "norm": "l2",
                "axis": "1",
                "return_norm": True,
            },
        },
        {
            "name": "k_means",
            "framework": "SKL",
            "params": {
                "n_clusters": "5",
                "init": "k-means++",
            },
        }
    ]
}

url = "http://127.0.0.1:5000/algorithm"


def send_request(url):
    print(json_ob)
    data_json = json.dumps(json_ob)
    print(data_json)
    r = requests.post(url, json=data_json)
    print("Status: {} \nRaw: {} \nData: {} \n".format(r.status_code, r.raw, r.json()))


if __name__ == '__main__':
    send_request(url)
    # print(json.dumps(datas))
    # os.path.join(PROJECT_ROOT, 'data', '')
