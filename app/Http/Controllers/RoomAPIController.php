<?php

namespace App\Http\Controllers;

use App\APIHelper;
use App\Room;
use App\RoomHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RoomAPIController extends Controller
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

    public function getAllRooms(Request $request) {
        $rooms = Room::all();
        return APIHelper::createPayload($rooms, Response::HTTP_OK);
    }

    public function createRoom(Request $request) {
        $body = $request->json()->all();
        $name = $body['name'];
        if (!RoomHelper::isRoomNameUnique($name)) {
            $errors = ["A room with the name ".$name." already exists."];
            return APIHelper::createError($errors, Response::HTTP_BAD_REQUEST);
        }

        $password = $body['password'];

        $room = Room::create([
            'id' => RoomHelper::generateNewRoomID(),
            'name' => $name,
            'password' => empty($password) ? '' : Hash::make($body['password'])
        ]);
        RoomHelper::createMembership($room, $password);

        return APIHelper::createPayload($room, Response::HTTP_OK);
    }

    public function getRoomById(Request $request) {
        $room_id = $request->route('id');
        $room = Room::find($room_id);

        if (!$room) {
            $errors = ["No room with the ID ".$room_id." exists."];
            return APIHelper::createError($errors, Response::HTTP_NOT_FOUND);
        }

        return APIHelper::createPayload($room, Response::HTTP_OK);
    }
}
