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
