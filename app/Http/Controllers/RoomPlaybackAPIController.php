<?php

namespace App\Http\Controllers;

use App\Events\PlaybackSent;
use App\Room;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SpotifyWebAPI\SpotifyWebAPI;

class RoomPlaybackAPIController extends Controller
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
        $this->middleware('auth.room.broadcaster')->except('getPlayback');
        $this->spotify_api = new SpotifyWebAPI();
        $this->spotify_api->setReturnType(SpotifyWebAPI::RETURN_ASSOC);
    }

    /**
     * Get the current broadcaster for the given Room.
     */
    public function getPlayback(Request $request)
    {
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

            $start_time = $this->getTimeInMilliseconds();
            $playback = $this->spotify_api->getMyCurrentPlaybackInfo();
            $end_time = $this->getTimeInMilliseconds();

            // Estimate latency time taken from Spotify's server to here to offset playback time.
            $half_roundtrip_time = intval(($end_time - $start_time)/2);

            return response()->json([
                'trackUri' => $playback['item']['uri'],
                'trackPosition' => $playback['progress_ms'] + $half_roundtrip_time,
                'isPaused' => !$playback['is_playing'],
                'timestamp' => $this->getTimeInMilliseconds()
            ], Response::HTTP_OK);
        }
    }

    public function sendPlayback(Request $request)
    {
        $body = $request->json()->all();

        $track_uri = isset($body['trackUri']) ? $body['trackUri'] : '';
        $track_position = isset($body['trackPosition']) ? $body['trackPosition'] : 0;

        $error = '';
        if (empty($track_uri)) {
            $error = "The Spotfiy track URI (trackUri) must be provided.";
        } else if (!isset($body["trackPosition"])) {
            $error = "The track position (trackPosition) must be provided,  in milliseconds.";
        } else if (!isset($body['isPaused'])) {
            $error = "The pause state (isPaused) must be provided, as a boolean.";
        }

        if (!empty($error)) {
            return response()->json(["error" => $error], Response::HTTP_BAD_REQUEST);
        }

        $is_paused = $body['isPaused'];

        $room_id = $request->route('id');
        $playback_sent = new PlaybackSent($room_id, $track_uri, $track_position, $is_paused);
        broadcast($playback_sent)->toOthers();

        return response()->json(null, Response::HTTP_OK);
    }

    private function getTimeInMilliseconds()
    {
        return round(microtime(true) * 1000);
    }
}
