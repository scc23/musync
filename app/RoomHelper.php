<?php

namespace App;

use App\RoomMembership;
use Auth;
use Illuminate\Support\Facades\Hash;

class RoomHelper
{
    /**
     * Generate a four character long unique ID.
     */
    public static function generateNewRoomID()
    {
        $id = str_random(4);
        # Generate new ID if found.
        while (Room::find($id) != null) {
          $id = str_random(4);
        }

        return $id;
    }

    /**
     * Determine if the provided Room name is unique.
     */
    public static function isRoomNameUnique($name)
    {
        $room_count = Room::where('name', $name)->count();

        return ($room_count == 0);
    }

    // TODO: Deprecate function, will no longer be used.
    public static function isMember($room)
    {
        $user_id = Auth::user()->id;

        $membership = RoomMembership::where('room_id', '=', $room->id)
                                    ->where('user_id', '=', $user_id)
                                    ->first();
        return ($membership != null);
    }

    public static function isRoomBroadcaster($room_id)
    {
        return true;
    }

    /**
     * Find the existing RoomMembership or create a new RoomMembership for
     * provided Room. If the Room is private, check if the provided Password
     * matches.
     */
    public static function createMembership($room, $password = '')
    {
        $user_id = Auth::user()->id;
        $membership = RoomMembership::where('room_id', '=', $room->id)
                                    ->where('user_id', '=', $user_id)
                                    ->first();
        if ($membership) {
            return $membership;
        }

        if (!empty($room->password) && !Hash::check($password, $room->password)) {
            return null;
        }

        return RoomMembership::create(['room_id'=>$room->id, 'user_id'=>$user_id]);
    }
}
