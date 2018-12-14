#!/bin/bash 

celery -A dm_combiner.celery worker --purge --loglevel=info
