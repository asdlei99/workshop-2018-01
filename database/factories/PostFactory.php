<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'description' => $faker->sentence(),
        'body' => $faker->paragraph(),
        'user_id' => 1,
        'views' => random_int(0,999),
    ];
});
