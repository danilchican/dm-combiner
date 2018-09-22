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


def log_validators(func):
    """
    Logging validators results.
    """
    @wraps(func)
    def decorated(*args, **kwargs):
        is_success, error = func(*args, **kwargs)
        if is_success:
            logger.info('{is_success}, {error} | {func}'.format(is_success=is_success, error=error, func=func.__name__))
        else:
            logger.info('{is_success} | {func}'.format(is_success=is_success, func=func.__name__))
        return is_success, error
    return decorated



