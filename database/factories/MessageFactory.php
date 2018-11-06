<?php

use Faker\Generator as Faker;

$factory->define(App\Message::class, function (Faker $faker) {
    return [
        'id' => rand(1,99),
        'user_id' => rand(1,99),
        'room_id' => str_random(4),
        'message' => $faker->realText(rand(10,60)),
    ];
});
