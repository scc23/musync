<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /*
      Creates a new Room with a randomly generated
      4 character ID.
    */
    public function create()
    {
        $id = str_random(4);
        # Generate new ID if found.
        while (Room::find($id) != null) {
          $id = str_random(4);
        }

        $data = [
            'id' => $id,
            'is_public' => True
        ];

        $room = Room::create($data);

        return redirect()->route('room_id', [ 'room_id' => $id ]);
    }
}
