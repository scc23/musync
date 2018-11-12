<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use SpotifyWebAPI;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * SpotifyWebAPI instancea.
     *
     * @var object
     */
    private $api;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->api = new SpotifyWebAPI\SpotifyWebAPI();
    }

    /**
     * Redirect the user to Spotify's authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('spotify')
            ->scopes(['streaming',
                    'user-read-birthdate',
                    'user-read-email',
                    'user-read-private',
                    'user-modify-playback-state',
                    'user-read-playback-state',
                    'playlist-modify-public',
                    'playlist-modify-private'])
            ->stateless(true)
            ->redirect();
    }

    /**
     * Obtain the user information from Spotify
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $spotifyUser = Socialite::with('spotify')->stateless(true)->user();
        } catch (\Exception $e) {
            return redirect('/');
        }
        $authUser = $this->userFindOrCreate($spotifyUser);
        auth()->login($authUser, true);

        return redirect()->to('/home');
    }

    /**
     * Invalidate api_token after logging out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function logout(Request $request) {
        $user = Auth::user();

        $this->guard()->logout();
        $request->session()->invalidate();

        $user->api_token = '';
        $user->save();

        return $this->loggedOut($request) ?: redirect('/');
    }

    private function userFindOrCreate($spotifyUser)
    {
        $user = User::where('spotify_id', $spotifyUser->id)->first();
        if ($user) {
            $user->api_token = $spotifyUser->token;
            $user->save();
        } else {
            $user = User::create([
                'spotify_id' => $spotifyUser->getId(),
                'name' => $spotifyUser->getName(),
                'api_token' => $spotifyUser->token,
                'refresh_token' => $spotifyUser->refreshToken,
            ]);
        }

        // Create an empty MuSync playlist for the user if it doesn't exist
        $playlistExists = false;
        $this->api->setAccessToken($user->api_token);
        $playlists = $this->api->getUserPlaylists($user->spotify_id);
        foreach ($playlists->items as $playlist) {
            if ($playlist->name == 'MuSync') {
                $playlistExists = true;
            }
        }
        if (!$playlistExists) {
            $this->api->createPlaylist(['name' => 'MuSync']);
        }

        return $user;
    }
}
