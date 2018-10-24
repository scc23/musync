<?php

namespace Tests\Feature;

use App\Room;
use App\RoomMembership;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class RoomsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a room without authentication (POST /rooms).
     */
    public function testPostRoomsWithoutAuthentication()
    {
        $this->assertTrue(Room::count() == 0);
        $this->assertTrue(RoomMembership::count() == 0);
        $this->post('/rooms');
        $this->assertTrue(Room::count() == 0);
        $this->assertTrue(RoomMembership::count() == 0);
    }

    /**
     * Test creating a room with authentication (POST /rooms).
     */
    public function testCreateRoomsWithAuthentication()
    {
        $user = factory(User::class)->create();

        $this->assertTrue(Room::count() == 0);
        $this->actingAs($user)
             ->call('POST', '/rooms', [
                 'create-room-name' => 'Test Room'
             ]);
        $this->assertTrue(Room::count() == 1);

        $room = Room::first();
        $this->assertEquals('Test Room', $room->name);
        $this->assertEquals('', $room->password);
    }

    /**
     * Test creating a room with a password (POST /rooms).
     */
    public function testCreateRoomsWithPassword()
    {
        $user = factory(User::class)->create();

        $this->assertTrue(Room::count() == 0);
        $this->actingAs($user)
             ->call('POST', '/rooms', [
                 'create-room-name' => 'Test Room',
                 'create-room-password' => 'Test Password'
             ]);
        $this->assertTrue(Room::count() == 1);

        $room = Room::first();
        $this->assertEquals('Test Room', $room->name);
        $this->assertTrue(Hash::check('Test Password', $room->password));
    }
}
