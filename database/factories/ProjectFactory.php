<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Project::class, function (Faker $faker) {
    return [
        'title'         => $faker->text(80),
        'normalize'     => $faker->boolean,
        'scale'         => $faker->boolean,
        'data_url'      => config('app.url') . '/stubs/fake_csv.csv',
        'columns'       => serialize(json_encode($faker->randomElements([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20], 10, true))),
        'configuration' => 'a:1:{i:0;a:3:{s:4:"name";s:7:"k_means";s:9:"framework";s:3:"SKL";s:6:"params";a:2:{s:10:"n_clusters";i:2;s:4:"init";s:9:"k-means++";}}}',
        'result'        => $faker->realText(),
    ];
});
