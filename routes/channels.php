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

Broadcast::channel('home', function($user) {
    return Auth::check();
});


Broadcast::channel('room.{room}', function ($user, Room $room) {
    return RoomHelper::isMember($room);
});
