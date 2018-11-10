<?php

use Faker\Generator as Faker;

$factory->define(App\Message::class, function (Faker $faker) {

    // Choose a random room-user membership
    $membership = App\RoomMembership::inRandomOrder()->first();
    
    return [
        'user_id' => $membership->user_id,
        'room_id' => $membership->room_id,
        'message' => $faker->realText(rand(10,60)),
    ];
});
