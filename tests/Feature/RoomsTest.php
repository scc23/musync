<?php

namespace Tests\Feature;

use App\Room;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test post to /rooms without authentication.
     *
     * @return void
     */
    public function testPostRoomsWithoutAuthentication()
    {
        $this->assertTrue(Room::count() == 0);
        $response = $this->post('/rooms');
        $this->assertTrue(Room::count() == 0);
    }

    /**
     * Test post to /rooms with authentication.
     *
     * @return void
     */
    public function testPostRoomsWithAuthentication()
    {
        $user = factory(User::class)->create();

        $this->assertTrue(Room::count() == 0);
        $response = $this->actingAs($user)
                         ->call('POST', '/rooms', [
                            'create-room-name' => "Test Room",
                            'create-room-password' => "Test Password"
                          ]);
        $this->assertTrue(Room::count() == 1);
    }
}
