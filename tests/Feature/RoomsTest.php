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

        # TODO - Add UI verification once views are created.
    }

    /**
     * Test post to /rooms without authentication.
     *
     * @return void
     */
    public function testPostRoomsWithAuthentication()
    {
        $user = factory(User::class)->create();

        $this->assertTrue(Room::count() == 0);
        $response = $this->actingAs($user)->post('/rooms');
        $this->assertTrue(Room::count() == 1);

        # TODO - Add UI verification once views are created.
    }
}
