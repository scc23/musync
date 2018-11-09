<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RefreshAccessTokenTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Test the refreshing of an access token (POST /api/token/refresh).
     */
    public function testRefreshAccessToken()
    {
        $user = factory(User::class)->create();
        $user->api_token = 'Test Access Token';
        $user->refresh_token = 'AQBK77zNYdJksKqYNPZw36FL4kolLr1qZOiX9xsEEtLrIl6TL4ac1nyusdLTAFbBon2hFNm8tVlLMAmTMIHzWwi2bMhGzoM6M09XrrVJ92hiEuhohkusy-z5FJZmUDIzS0wQ2Q';
        $user->save();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST','/api/token/refresh');
        $response->assertOk();
        $this->assertFalse(Hash::check('Test Access Token', $user->api_token));
    }
}
