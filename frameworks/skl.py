from sklearn import cluster, preprocessing
from sklearn.decomposition import PCA


class SKL:

    @staticmethod
    def normalize(data, norm='l2', axis=1, copy=True, return_norm=False):
        data = preprocessing.normalize(X=data, norm=norm, axis=axis, copy=copy, return_norm=return_norm)
        return data

    @staticmethod
    def scale(data, axis=0, with_mean=True, with_std=True, copy=True):
        data = preprocessing.scale(X=data, axis=axis, with_mean=with_mean, copy=copy, with_std=with_std)
        return data

    @staticmethod
    def k_means(data, n_clusters, max_iter=300, random_state=None, init='k-means++'):
        clusters_info = cluster.KMeans(n_clusters=n_clusters, max_iter=max_iter, random_state=random_state, init=init)

        # compute cluster centers and predict cluster index for each sample
        cluster_indexes = clusters_info.fit_predict(data)

        clusters_info_dict = {'centers': clusters_info.cluster_centers_, 'indexes': cluster_indexes,
                              'inertia': clusters_info.inertia_, 'labels': clusters_info.labels_}
        return clusters_info_dict

    @staticmethod
    def data_reduction(data, n_components=2):
        pca = PCA(n_components=n_components).fit(data)
        data = pca.transform(data)
        return da