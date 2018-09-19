from functools import wraps

import pandas as pd

from utils.logger import logger


def log_result(func):
    """
    Logging results.
    """
    @wraps(func)
    def decorated(*args, **kwargs):
        out_condition = func(*args, **kwargs)
        if isinstance(out_condition, pd.DataFrame):
            log_out_condition = out_condition.head()
        else:
            log_out_condition = out_condition
        logger.info('Log results of {name}() | Result:\n {result}'.format(result=log_out_condition, name=func.__name__))
        return out_condition
    return decorated


def exception(func):
    """
    Wrap up function with try-except.
    """
    @wraps(func)
    def decorated(*args, **kwargs):
        try:
            out_condition = func(*args, **kwargs)
        except Exception as ex:
            logger.warning('{name}()'.format(name=func.__name__))
            logger.warning('{}: {}'.format(type(ex).__name__, ex))
            return None
        return out_condition
    return decorated


