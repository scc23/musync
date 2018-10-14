<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RoomController extends Controller
{
    /*
      Creates a new Room with a randomly generated
      4 character ID.
    */
    public function create(Request $request)
    {
        $id = str_random(4);
        # Generate new ID if found.
        while (Room::find($id) != null) {
          $id = str_random(4);
        }

        $name = $request->input('create-room-name');

        $password = $request->input('create-room-password');
        if (empty($password)) {
            $password = "";
        } else {
            $password = Hash::make($password);
        }

        $room = Room::create([
            'id' => $id,
            'name' => $name,
            'password' => $password
        ]);

        return redirect()->route('room_id', [ 'room_id' => $id ]);
    }
}
