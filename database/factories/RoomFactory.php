<?php

use Faker\Generator as Faker;

$factory->define(App\Room::class, function (Faker $faker) {
    
    return [
        'id' => str_replace( ' ', '', $faker->unique()->bothify('****') ),
        'name' => $faker->bothify('?????##'),
        'password' => ''

    ];
});
