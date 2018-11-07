<?php

use App\RoomMembership;
use Faker\Generator as Faker;


$factory->define(App\RoomMembership::class, function (Faker $faker) {

    $room_id = App\Room::inRandomOrder()->first()->id;
    $user_id = App\User::inRandomOrder()->first()->id;
    
    // TODO: Conditional check doesn't work
    $key_exists = function($room_id, $user_id) {
        return RoomMembership::where([ 'room_id' => $room_id, 'user_id' => $user_id, ])->exists();
    }; 

    do {
        $user_id = factory(App\User::class)->create()->id;    
    } while ( $key_exists($room_id, $user_id) );

    
    return [
        'room_id' => $room_id,
        'user_id' => $user_id,
    ];
});
