<?php

use Faker\Generator as Faker;

$factory->define(\App\Comment::class, function (Faker $faker) {

    return [
        'post_id' => $faker->numberBetween(5,10),
        'user_id' => $faker->numberBetween(1,5),
        'parent_id' => 0,
        'level' => 1,
        'body'  => $faker->sentence(),
    ];
});
