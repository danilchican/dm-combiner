from sklearn import cluster, preprocessing
from sklearn.decomposition import PCA
import pandas as pd

from frameworks.framework import Framework


class SKL(Framework):

    @staticmethod
    def normalize(data: pd.DataFrame, norm: str='l2', axis: int=1, copy: bool=True, return_norm: bool=False) -> pd.DataFrame:
        data = preprocessing.normalize(X=data, norm=norm, axis=axis, copy=copy, return_norm=return_norm)
        return data

    @staticmethod
    def scale(data: pd.DataFrame, axis: int=0, with_mean: bool=True, with_std: bool=True, copy: bool=True) -> pd.DataFrame:
        data = preprocessing.scale(X=data, axis=axis, with_mean=with_mean, copy=copy, with_std=with_std)
        return data

    @staticmethod
    def k_means(data: pd.DataFrame, n_clusters: int, max_iter: int=300, random_state: int=None, init: str='k-means++') -> dict:
        clusters_info = cluster.KMeans(n_clusters=n_clusters, max_iter=max_iter, random_state=random_state, init=init)

        # compute cluster centers and predict cluster index for each sample
        cluster_indexes = clusters_info.fit_predict(data)

        clusters_info_dict = {'centers': clusters_info.cluster_centers_, 'indexes': cluster_indexes,
                              'inertia': clusters_info.inertia_, 'labels': clusters_info.labels_}
        return clusters_info_dict

    @staticmethod
    def data_reduction(data: pd.DataFrame, n_components: int=2) -> pd.DataFrame:
        pca = PCA(n_components=n_components).fit(data)
        data = pca.transform(data)
        return data


class Theano(Framework):
    pass


if __name__ == "__main__":
    lib_dict = SKL.__dict__
    methods = SKL().methods
    print(methods)
    print(SKL().methods_params)
