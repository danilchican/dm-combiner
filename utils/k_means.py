import pandas as pd
from sklearn.cluster import KMeans
from sklearn.preprocessing import scale
from utils.data_handler import DataHandler
from utils.skl import SKL
from utils import logger


def k_means(data, number_of_clusters, max_iter=300):
    logger.logger.info('Started k_means')
    clusterer = KMeans(n_clusters=number_of_clusters, max_iter=max_iter, random_state=10, init='k-means++')
    # compute cluster centers and predict cluster index for each sample
    cluster_indexes = clusterer.fit_predict(data)
    return clusterer.cluster_centers_, cluster_indexes, clusterer.inertia_


def read_data_from_file(path):
    logger.logger.info('Started reading from file')
    df = pd.read_csv(path)
    df.rename(columns={x: y for x, y in zip(df.columns, range(0, len(df.columns)))}, inplace=True)
    return df


def normalize_data(data_frame, column_indexes):
    logger.logger.info('Started normalize_data')
    normalized_data = pd.DataFrame(scale(data_frame[column_indexes]))
    return normalized_data


def process(file_path, column_indexes, number_of_clusters, is_normalize=True):
    logger.logger.info('Started process')
    df = read_data_from_file(path=file_path)
    if is_normalize:
        df = normalize_data(data_frame=df, column_indexes=column_indexes)

    centers, cluster_labels, sum_of_distances = k_means(df, number_of_clusters)
    print('centers {}\n  cluster_labels {}\n sum_of_distances {}\n'.format(centers, cluster_labels, sum_of_distances))
    return centers


if __name__ == '__main__':
    # process('../Data/telecom_churn.csv', [6, 7, 8], 4)
    data = DataHandler.read_data_csv('../data/telecom_churn.csv')
    data = DataHandler.convert_column_names_to_numbers(data)
    data = SKL.normalize(data[[6, 7, 8]])
    print(data[[6, 7, 8]])
