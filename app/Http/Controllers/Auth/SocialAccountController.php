<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use DebugBar;

class SocialAccountController extends Controller
{
    /**
     * Redirect the user to provider's (i.e Spotify) authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless(true)->redirect();
    }
    /**
     * Obtain the user information from provider (i.e Spotify).
     *
     * @return Response
     */
    public function handleProviderCallback(\App\SocialAccountService $accountService, $provider)
    {
        try {
            $user = Socialite::with($provider)->stateless(true)->user();
        } catch (\Exception $e) {
            return redirect('/');
        }
        
        $authUser = $accountService->findOrCreate(
            $user,
            $provider
        );
        
        auth()->login($authUser, true);
        
        return redirect()->to('/home');
    }
}
