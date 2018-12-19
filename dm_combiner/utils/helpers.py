import os
from datetime import datetime
from hashlib import sha512

import matplotlib.cm as cm
import matplotlib.pyplot as plt
from dm_combiner.config import Config
from werkzeug.utils import secure_filename


def save_features_2d(fname, x, y, x_label='X Label', y_label='Y Label', title='Title', grid=True):
    fig, ax = plt.subplots()
    ax.scatter(x, y)

    ax.set_xlabel(x_label, fontsize=15)
    ax.set_ylabel(y_label, fontsize=15)
    ax.set_title(title, fontsize=15)

    ax.grid(grid)
    plt.savefig(get_path_for_saving_image(fname))


def get_color_code(values, color_groups):
    cmap = cm.get_cmap("Spectral")
    return cmap(values / color_groups)


def save_clusters_2d(fname, data, centers, colors, x_label='X Label', y_label='Y Label', title='Title', grid=True):
    fig, ax1 = plt.subplots()
    ax1.scatter(data[:, 0], data[:, 1], marker='.', s=30, lw=0, alpha=0.7, c=colors)

    # Draw white circles at cluster centers
    ax1.scatter(centers[:, 0], centers[:, 1], marker='o', c="white", alpha=1, s=200)

    for i, c in enumerate(centers):
        ax1.scatter(c[0], c[1], marker='$%d$' % i, alpha=1, s=50)

    ax1.set_title(title)
    ax1.set_xlabel(x_label)
    ax1.set_ylabel(y_label)
    ax1.grid(grid)

    plt.savefig(get_path_for_saving_image(fname))


def get_path_for_saving_image(fname):
    path = os.path.join(Config().PROJECT_ROOT, 'images', fname)
    return path


def save_file(file):
    extension = file.filename.rsplit('.', 1)[1].lower()
    filename = file.filename + str(datetime.now())
    hash_filename = sha512(filename.encode('utf-8')).hexdigest()
    filename = secure_filename(hash_filename[:20])
    filename = f"{filename}.{extension}"
    file_path = os.path.join(Config().STATIC_FILES, filename)
    file.save(file_path)
    return file_path
