<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Project::class, function (Faker $faker) {
    return [
        'title'         => $faker->text(80),
        'normalize'     => $faker->boolean,
        'scale'         => $faker->boolean,
        'data_url'      => $faker->url,
        'columns'       => json_encode($faker->randomElements([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20], 10, true)),
        'configuration' => $faker->text,
        'result'        => $faker->realText(),
    ];
});
