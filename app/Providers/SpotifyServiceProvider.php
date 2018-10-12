<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SpotifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('SpotifyWebApi\SpotifyWebApi', function ($app) {
            $client = new SpotifyWebApi\SpotifyWebApi;

            $session = new SpotifyWebAPI\Session(
                env('SPOTIFY_CLIENT_ID'),
                env('SPOTIFY_CLIENT_SECRET'),
                env('SPOTIFY_CALLBACK_URL')
            );

            $scopes = [
                'user-modify-playback-state',
                'user-read-currently-playing',
                'user-read-playback-state'
            ];

            $session->requestCredentialsToken($scopes);

            $accessToken = $session->getAccessToken();

            $client->setAccessToken($accessToken);

            return $client;
        });
    }
}
