<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to Spotify's authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('spotify')->stateless(true)->redirect();
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

    private function userFindOrCreate($spotifyUser)
    {
        $user = User::where('spotify_id', $spotifyUser->id)->first();
        if (!$user) {
            return User::create([
                'spotify_id' => $spotifyUser->getId(),
                'name' => $spotifyUser->getName(),
                'token' => $spotifyUser->token,
                'refresh_token' => $spotifyUser->refreshToken,
            ]);
        }
        return $user;
    }
}
