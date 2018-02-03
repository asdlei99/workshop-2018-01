<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'description' => $faker->sentence(),
        'body' => $faker->paragraph(),
        'user_id' => 2,
        'view' => random_int(0,999),
    ];
});
