import logging
from conf.config import CONFIG

logger = logging.getLogger("dm-combiner")
logging.basicConfig(
    format=CONFIG.log_format,
    datefmt=CONFIG.log_date_format,
    # filename=CONFIG.log_path,
    level=CONFIG.log_level,
)
