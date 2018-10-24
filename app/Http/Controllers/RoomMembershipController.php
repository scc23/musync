<?php

namespace App\Http\Controllers;

use App\Room;
use App\RoomHelper;
use App\RoomMembership;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RoomMembershipController extends Controller
{

    /**
     * Handle request (POST /room/{id}/join for creating new RoomMembership for the Room
     * with the given ID.
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function join(Request $request) {
        $room_id = $request->route('id');
        $room = Room::find($room_id);

        if (!RoomHelper::isMember($room)) {
            $password = Input::get('join-room-password');

            $membership = RoomHelper::createMembership($room, $password);
            if (!$membership) {
              return response('User is not authorized to access this room, invalid password.',
                              Response::HTTP_UNAUTHORIZED);
            }
        }

        return redirect()->route('room.id', ['id' => $room->id]);
    }
}
