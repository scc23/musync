<?php

namespace App\Http\Controllers;

use App\Events\JoinedRoom;
use App\Room;
use App\RoomHelper;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomMembershipAPIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Attempt to join the room by ID (provided in the URL) using the password
     * contained within the JSON body.
     */
    public function joinRoom(Request $request) {
        $room_id = $request->route('id');
        $room = Room::find($room_id);
        if (!$room) {
            $error = 'No room with the ID '.$room_id.' exists.';
            return response()->json(['joinIdError' => $error], Response::HTTP_NOT_FOUND);
        }

        $body = $request->json()->all();
        $password = isset($body['password']) ? $body['password'] : '';
        $membership = RoomHelper::createMembership($room, $password);
        if (!$membership) {
            $error = 'Incorrect password provided.';
            return response()->json(['joinPasswordError' => $error], Response::HTTP_UNAUTHORIZED);
        }

        broadcast(new JoinedRoom($room_id, Auth::id()));

        return response()->json($membership, Response::HTTP_OK);
    }
}
