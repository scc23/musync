<?php

namespace App\Http\Controllers;

use App\Room;
use App\RoomHelper;
use App\RoomMembership;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomMembershipController extends Controller
{

    /**
     * Create a new room membership.
     *
     * @param  array  $data
     * @return \App\RoomMembership
     */
    protected function create($data) {
        // return
    }


    public function join(Request $request) {
        $id = $request->route("id");
        $room = Room::find($id);
        if (!$room) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        if (!RoomHelper::isMember($room)) {
            $password = '';
            if ($request->session()->has('password')) {
                $password = $request->session()->get('password');
            } else {
                $password = $request->input('join-room-password');
            }

            $joined = RoomHelper::joinRoom($room, $password);
            if (!$joined) {
              return response('User is not authorized to access this room, invalid password.',
                              Response::HTTP_UNAUTHORIZED);
            }
        }

        return redirect()->route('room.id', ['id' => $room->id]);
    }
}
