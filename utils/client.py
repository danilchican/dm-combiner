import requests
import json


datas = {'file_path': 'Data/telecom_churn.csv', 'column_indexes':  [5, 6, 7, 8], 'number_of_clusters': 6,
         'is_normalize': True}
url = "http://127.0.0.1:5000/proccess_json"


def send_request(url):
    data_json = json.dumps(datas)
    r = requests.post(url, json=data_json)
    print("Status: {} \nRaw: {} \nData: {} \n".format(r.status_code, r.raw, r.json()))


if __name__ == '__main__':
    send_request(url)