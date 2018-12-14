import logging
from dm_combiner import config

logger = logging.getLogger("dm-combiner")
logging.basicConfig(
    format=config.LOG_FORMAT,
    datefmt=config.LOG_DATE_FORMAT,
    # filename=config.LOG_PATH,
    level=config.LOG_LEVEL,
)
