from dm_combiner import celery


@celery.task
def my_background_task(arg1, arg2):
    return arg1 + arg2
