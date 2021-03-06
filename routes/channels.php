<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Room;
use App\RoomHelper;
use App\User;

Broadcast::channel('home', function($user) {
    return Auth::check();
});

Broadcast::channel('home.{userId}', function($user, $userId) {
    return $user->id == $userId;
});

Broadcast::channel('room.{room}', function ($user, Room $room) {
    return RoomHelper::isMember($room);
});


Broadcast::channel('room-presence.{room}', function ($user, Room $room) {
    if (RoomHelper::isMember($room)) {
        return [
            'id' => $user->id,
            'name' => $user->name
        ];
    }
});
