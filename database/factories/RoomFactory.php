<?php

use Faker\Generator as Faker;

$factory->define(App\Room::class, function (Faker $faker) {
    
    return [
        'id' => str_random(4),
        'name' => $faker->name,
        'password' => ''
    ];
});
