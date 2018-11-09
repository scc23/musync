<?php

use App\RoomMembership;
use Faker\Generator as Faker;


$factory->define(App\RoomMembership::class, function (Faker $faker) {

    // It is not possible to generate Room-User pair with existing tables.
    // Factory doesn't seed 1 by 1 but create some sort of cache before input data into the table all at once,
    // so we cannot check if the pair exists yet, and might create SQL conflicts.
    // However, we can choose a random room first and create more users accordingly.

    $room_id = App\Room::inRandomOrder()->first()->id;
    $user_id = factory(App\User::class)->create()->id;
    
    return [
        'room_id' => $room_id,
        'user_id' => $user_id,
    ];
});
