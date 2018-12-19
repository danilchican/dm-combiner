# dm-combiner
## DataMining Combiner ##

base_url: http://104.248.26.47:5000/

romaresh.me:5000 also availbale but response time may increase

__API METHODS__

- get files list `[GET]`: /files

Request:`104.248.26.47:5000/files`

Response:
```json
{
    "files": [
        "telecom_churn.csv",
        "iris.txt",
        "iris.data",
        "telecom_churn.info",
        "telecom_churn.html"
    ],
    "success": true
}
```
- download file `[GET]`: /get_file/<filename>

Request: `104.248.26.47:5000/get_file/telecom_churn.csv`

Response:file

- get file path `[GET]`: /get_file_path/<filename>

Request: `romaresh.me:5000/get_file_path/telecom_churn.csv`

Response:
```son
{
    "result": "/home/roma/work/dm-combiner/data/telecom_churn.csv",
    "success": true
}
```
- upload_file (in form should be file) `[POST]`: /upload_file

Request: `104.248.26.47:5000/upload_file` (file should be send in form with key `file`)

Response:
```json
{
    "path": "/home/roma/dm-combiner/dm_combiner/data/telecom_churn.csv",
    "success": true
}
```
- get list of frameworks `[GET]`: /frameworks

Request: `104.248.26.47:5000/frameworks`

Response:
```json
{
    "frameworks": [
        {
            "methods": [
                "normalize",
                "scale",
                "k_means",
                "data_reduction"
            ],
            "name": "SKL"
        }
    ],
    "success": true
}
```

- get args for methods `[GET]`: /args/<framework_name>/<command>

Request: `104.248.26.47:5000/args/SKL/normalize`

Response:
```json
{
    "args": [
        {
            "name": "norm",
            "type": "str"
        },
        {
            "name": "axis",
            "type": "int"
        },
        {
            "name": "copy",
            "type": "bool"
        },
        {
            "name": "return_norm",
            "type": "bool"
        }
    ],
    "success": true
}
```

- algorithm `[POST]`: /algorithm

Request: `104.248.26.47:5000/algorithm`
```json
{
    "config": {
        "normalize": true,
        "scale": true,
        "file_url": "/home/roma/dm-combiner/dm_combiner/data/telecom_churn.csv",
        "columns": [6, 7, 8, 9],
        "callback_url": "http://romaresh.me:5000/"
    },
    "commands": [
    	{
            "name": "data_reduction",
            "framework": "SKL",
            "params": {
            }
        },
        {
            "name": "k_means",
            "framework": "SKL",
            "params": {
                "n_clusters": 2,
                "init": "k-means++"
            }
        }
    ]
}
```


Response:
```json
{
    "success": true,
    "task_id": "93140863-e2ae-4913-afe1-6be949794b04"
}
```

- get status of algorithm `[POST]`: /status/<task_id>

Response: `104.248.26.47:5000/status/93140863-e2ae-4913-afe1-6be949794b04`
```json
{
    "state": "SUCCESS"           # available: 'SUCCESS', "PENDING", "FAILED"
}
```
