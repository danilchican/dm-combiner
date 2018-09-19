import logging
from conf.config import CONFIG

logger = logging.getLogger("dce_analyzer")
logging.basicConfig(
    format=CONFIG.log_format,
    datefmt=CONFIG.log_date_format,
    # filename=CONFIG.log_path,
    level=CONFIG.log_level,
)