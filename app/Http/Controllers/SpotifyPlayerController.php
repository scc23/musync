<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Events\SpotifyPlayerAction;

class SpotifyPlayerController extends Controller
{
    /**
     * Handle request (POST /updateState) for Spotify player control
     *
     * @param  Illuminate\Http\Request $request
     */
    public function sendRequest(Request $request)
    {
        $user = Auth::user();
        $request = "Test string";

        event(new SpotifyPlayerAction($user, $request));

        return ["status" => "Message Sent!"];
    }
}
