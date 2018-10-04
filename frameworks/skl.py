from sklearn import cluster, preprocessing
from sklearn.decomposition import PCA
import inspect


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
        print(11111, data, n_clusters)
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
        return data

    @property
    def methods(self):
        methods = {}
        for method_name in self.__class__.__dict__:
            method_obj = getattr(self.__class__, method_name)
            if callable(method_obj) and not method_name.startswith("__"):
                methods[method_name] = method_obj
        return methods

    @property
    def methods_params(self):
        methods_params_dict = {}
        for method_name in self.methods:
            methods_params_dict[method_name] = inspect.getargspec(getattr(self.__class__, method_name))[0]
        return methods_params_dict


if __name__ == "__main__":
    lib_dict = SKL.__dict__
    methods = SKL().methods
    print(methods)
    print(SKL().methods_params)