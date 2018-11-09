<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Auth;
use SpotifyWebAPI;

class RefreshTokenController extends Controller
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

    public function getNewAccessToken()
    {
        $user = Auth::user();
        $session = new SpotifyWebAPI\Session(
            \Config::get('app.spotify_client_id'),
            \Config::get('app.spotify_client_secret'),
            \Config::get('app.spotify_callback_url')
        );

        // Refresh the access token
        $session->refreshAccessToken($user->refresh_token);

        // Save the new access token in the database
        $user->api_token = $session->getAccessToken();
        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }
}
