<?php

namespace App\Http\Controllers;

use App\Room;
use App\RoomBroadcaster;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomBroadcastAPIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'auth.room']);
    }

    /**
     * Get the current broadcaster for the given Room.
     */
    public function getBroadcaster(Request $request) {
        $room_id = $request->route('id');
        $room = Room::find($room_id);

        $broadcaster = $room->broadcaster;

        if (empty($broadcaster)) {
            return response()->json([
                'room_id' => $room_id,
                'error' => 'There is currently no broadcaster for this room.'
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json($broadcaster, Response::HTTP_OK);
        }
    }

    /**
     * Allow calling user to become RoomBroadcaster if none exists.
     */
    public function beginBroadcast(Request $request)
    {
        $room_id = $request->route('id');
        $room = Room::find($room_id);

        if (!empty($room->broadcaster)) {
            return response()->json([
                'room_id' => $room_id,
                'error' => 'This room already currently has a broadcaster.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $broadcaster = RoomBroadcaster::create([
            'room_id' => $room->id,
            'user_id' => Auth::user()->id
        ]);

        return response()->json($broadcaster, Response::HTTP_OK);
    }

    /**
     * Give up RoomBroadcaster status if calling user is currently broadcasting.
     */
    public function stopBroadcast(Request $request)
    {
        $room_id = $request->route('id');
        $room = Room::find($room_id);

        $broadcaster = $room->broadcaster;
        if (!$this->isBroadcaster($broadcaster)) {
            return response()->json([
                'room_id' => $room_id,
                'error' => 'Invalid request, calling user is either not the broadcaster or there is no broadcaster for the room.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $broadcaster->delete();

        return response()->json($broadcaster, Response::HTTP_OK);
    }

    /**
     * Determine whether the calling user is the broadcaster for the current room.
     */
    private function isBroadcaster($broadcaster)
    {
        if (empty($broadcaster)) {
            return false;
        }

        return $broadcaster->user_id == Auth::user()->id;
    }
}
