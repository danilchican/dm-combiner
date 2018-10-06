# dm-combiner
## DataMining Combiner ##

base_url: http://romaresh.me:5000/

__API METHODS__

- get files list `[GET]`: /files

Request:`romaresh.me:5000/files`

Response:
```json
{
    "result": [
        "telecom_churn.csv",
        "telecom_churn.info",
        "telecom_churn.html",
        "iris.data",
        "telecom_churn_1.csv",
        "iris.txt",
        "colors.json"
    ],
    "success": true
}
```
- download file `[GET]`: /get_file/<filename>

Request: `romaresh.me:5000/get_file/telecom_churn.csv`

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

Request: `romaresh.me:5000/upload_file`

Response:
```json
{
    "result": "/home/roma/work/dm-combiner/data/telecom_churn_1.csv",
    "success": true
}
```
- get list of frameworks `[GET]`: /frameworks

Request: `romaresh.me:5000/frameworks`

Response:
```json
{
    "result": [
        "SKL",
        "Theano"
    ],
    "success": true
}
```
- get list of commands for framework `[GET]`: /commands/<framework_name>

Request: `romaresh.me:5000/commands/SKL`

Response:
```json
{
    "result": [
        "normalize",
        "scale",
        "k_means",
        "data_reduction"
    ],
    "success": "true"
}
```
- get list of mandatory commands `[GET]`: /mandatory_commands

Request: `romaresh.me:5000/mandatory_commands`

Response:
```json
{
    "result": [
        "load",
        "save"
    ],
    "success": "true"
}
```
- get list of params for mandatory commands `[GET]`: /mandatory_commands/params/<command_name>

Request: `romaresh.me:5000/mandatory_commands/params/load`

Response:
```json
{
    "result": [
        "path",
        "columns",
        "is_normalize",
        "is_scale"
    ],
    "success": "true"
}
```

- get params for command `[GET]`: /params/<framework_name>/<command_name>

Request: `romaresh.me:5000/params/SKL/normalize`

Response:
```json
{
    "result": [
        "data",
        "norm",
        "axis",
        "copy",
        "return_norm"
    ],
    "success": "true"
}
```

- algorithm `[POST]`: /algorithm

Request: `romaresh.me:5000/algorithm`
```json
{
    "command_1": {
        "name": "load",
        "framework": "scikit",

        "params": {
            "path": "/home/roma/work/dm-combiner/data/telecom_churn.csv",
            "columns": [1,2,17]
        }
    },

    "command_2": {
        "name": "normalize",
        "framework": "scikit",

        "params": {
        }
    },
    
     "command_3": {
        "name": "k_means",
        "framework": "scikit",

        "params": {
            "n_clusters": 3
            }
    },
    
        "command_4": {
        "name": "save",
        "framework": "scikit",

        "params": {
        }
    }

}
```


Response:
```json
{
    "result": {
        "centers": [
            [
                0.2256543332700875,
                0.9737066016612957,
                0.006212603729089694
            ],
            [
                0.3302144821283248,
                0.9428148527121636,
                0.006203442777769738
            ],
            [
                0.11869851760604402,
                0.9920263802526129,
                0.006133546348963394
            ]
        ],
        "indexes": [
            1,
            0,
            1,
            0,
            0,
            0,
            0,
            1,
            0,
            1,
            2,
            ....
```