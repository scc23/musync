<?php

namespace App\Http\Controllers;

use App\Room;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SpotifyWebAPI\SpotifyWebAPI;

class RoomPlaybackController extends Controller
{
    private $spotify_api;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'auth.room']);
        $this->spotify_api = new SpotifyWebAPI();
        $this->spotify_api->setReturnType(SpotifyWebAPI::RETURN_ASSOC);
    }

    /**
     * Get the current broadcaster for the given Room.
     */
    public function getPlayback(Request $request) {
        $room_id = $request->route('id');
        $room = Room::find($room_id);

        $broadcaster = $room->broadcaster;

        if (empty($broadcaster)) {
            return response()->json([
                'room_id' => $room_id,
                'error' => 'There is currently no broadcaster for this room.'
            ], Response::HTTP_NOT_FOUND);
        } else {
            $broadcasting_user = User::find($broadcaster->user_id);
            $this->spotify_api->setAccessToken($broadcasting_user->api_token);
            $playback = $this->spotify_api->getMyCurrentPlaybackInfo();

            return response()->json([
                'trackUri' => $playback['item']['uri'],
                'trackPosition' => $playback['progress_ms'],
                'isPaused' => !$playback['is_playing']
            ], Response::HTTP_OK);
        }
    }
}
