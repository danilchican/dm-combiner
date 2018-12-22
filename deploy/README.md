# DM_COMBINER_API

Deploy instruction:
1) install Python3.7 or Python3.6 (`python3.7_installation`  contain script for installing Python3.7 on Ubuntu 16.04(or another debian based distributive)
2) install pip3 
3) install `pipenv` from pip3
4) install `redis` on your host, that is needed for deffered tasks from celery (https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-redis-on-ubuntu-16-04)
7) cd to project folder
5) create config file `dm_combiner/config/config.yaml` (example here: https://pastebin.com/VSwVZYNu)
8) run `pipenv install --python3.7`
9) run `pipenv shell`
10) run `python app.py`
11) if you want to be able to run deffered tasks, run celery `./run_celery.sh`
