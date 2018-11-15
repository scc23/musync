<?php

namespace Tests\Feature;

use App\RoomMembership;

class TestHelper
{
    public static function createMembership($room, $user)
    {
        return factory(RoomMembership::class)->create([
            'room_id' => $room->id, 'user_id' => $user->id
        ]);
    }
}
