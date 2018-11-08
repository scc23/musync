<?php

namespace App\Http\Controllers;

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

    /**
     * Fetch all Rooms from the database.
     */
    public function getAllRooms(Request $request) {
        $rooms = Room::all();
        foreach ($rooms as $room) {
            $room['hasAccess'] = $this->userHasAccess($room);
        }

        return response()->json($rooms, Response::HTTP_OK);
    }

    /**
     * Create a new room with the provided name and password, contained within
     * the request body as JSON.
     */
    public function createRoom(Request $request) {
        $body = $request->json()->all();
        $name = isset($body['name']) ? $body['name'] : '';

        $error = '';
        if (empty($name)) {
            $error = 'A name must be provided to create a room.';
        } else if (!RoomHelper::isRoomNameUnique($name)) {
            $error = 'A room with the name '.$name.' already exists.';
        }
        if (!empty($error)) {
            return response()->json(["createNameError" => $error], Response::HTTP_BAD_REQUEST);
        }

        $password = isset($body['password']) ? $body['password'] : '';
        $room = Room::create([
            'id' => RoomHelper::generateNewRoomID(),
            'name' => $name,
            'password' => empty($password) ? '' : Hash::make($password)
        ]);
        RoomHelper::createMembership($room, $password);

        return response()->json($room, Response::HTTP_OK);
    }

    /**
     * Fetch a single room by ID.
     */
    public function getRoomById(Request $request) {
        $room_id = $request->route('id');
        $room = Room::find($room_id);

        if (!$room) {
            $error = 'No room with the ID '.$room_id.' exists.';
            return response()->json(['error' => $error], Response::HTTP_NOT_FOUND);
        }

        $room['hasAccess'] = $this->userHasAccess;

        return response()->json($room, Response::HTTP_OK);
    }

    private function userHasAccess($room) {
        return (!$room['isPrivate'] || RoomHelper::isMember($room));
    }
}
