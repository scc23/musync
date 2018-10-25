<?php

namespace App;

use App\RoomMembership;
use Auth;
use Illuminate\Support\Facades\Hash;

class RoomHelper
{
    public static function generateNewRoomID() {
        $id = str_random(4);
        # Generate new ID if found.
        while (Room::find($id) != null) {
          $id = str_random(4);
        }

        return $id;
    }

    public static function isRoomNameUnique($name) {
        $room_count = Room::where('name', $name)->count();

        return ($room_count == 0);
    }

    public static function isMember($room)
    {
        $user_id = Auth::user()->id;

        $membership = RoomMembership::where('room_id', '=', $room->id)
                                    ->where('user_id', '=', $user_id)
                                    ->first();
        return ($membership != null);
    }

    public static function createMembership($room, $password = '')
    {
      if (!empty($room->password) && !Hash::check($password, $room->password)) {
          return null;
      }

      $user_id = Auth::user()->id;
      return RoomMembership::create(['room_id'=>$room->id, 'user_id'=>$user_id]);
    }
}
