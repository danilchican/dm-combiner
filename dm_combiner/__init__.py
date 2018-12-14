from celery import Celery
from flask import Flask
from raven.contrib.flask import Sentry

from dm_combiner.config import Config
from dm_combiner.utils import logger

config = Config()
sentry = Sentry(logging=True, level='ERROR', dsn=config.SENTRY_DSN)
celery = Celery(__name__, broker=config.CELERY_BROKER_URL, backend=config.CELERY_RESULT_BACKEND,
                include=['dm_combiner.api.tasks'])


def create_app():
    app = Flask(__name__)
    app.config.from_object(config)
    celery.conf.update(app.config)
    sentry.init_app(app)

    from dm_combiner.api.routes import api

    app.register_blueprint(api)

    app.logger.addHandler(logger.logger)

    return app
