web: gunicorn app:app
celeryd: celery -A dm_combiner.celery worker --purge --loglevel=info
