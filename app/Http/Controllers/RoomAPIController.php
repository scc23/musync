<?php

namespace App\Http\Controllers;

use App\Room;
use App\RoomHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Auth;
use SpotifyWebAPI;

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
        return response()->json($payload, Response::HTTP_OK);
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

        // Create an empty collaborative playlist for the room
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken(Auth::user()->api_token);
        // Save playlist id in the room table
        $playlistData = $api->createPlaylist(['name' => 'MuSync', 'public' => false, 'collaborative' => true]);

        $password = isset($body['password']) ? $body['password'] : '';
        $room = Room::create([
            'id' => RoomHelper::generateNewRoomID(),
            'name' => $name,
            'password' => empty($password) ? '' : Hash::make($password),
            'playlist_id' => $playlistData->id
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

        return response()->json($room, Response::HTTP_OK);
    }
}
