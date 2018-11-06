<?php

namespace Tests\Feature;

use App\Room;
use App\RoomMembership;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RoomsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a room without authentication (POST /api/rooms).
     */
    public function testCreateRoomsWithoutAuthentication()
    {
        $this->assertTrue(Room::count() == 0);
        $this->assertTrue(RoomMembership::count() == 0);
        $response = $this->json('POST','/api/rooms', [
            'name' => 'Test Room',
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertTrue(Room::count() == 0);
        $this->assertTrue(RoomMembership::count() == 0);
    }

    /**
     * Test creating a room without providing a name (POST /api/rooms).
     */
    public function testCreateRoomsWithoutName()
    {
      $user = factory(User::class)->create();

      $this->assertTrue(Room::count() == 0);
      $response = $this->withHeaders([
          'Authorization' => 'Bearer '.$user->api_token
      ])->json('POST','/api/rooms');
      $response->assertStatus(Response::HTTP_BAD_REQUEST);
      $this->assertTrue(Room::count() == 0);
    }

    /**
     * Test creating a room with a name that already exists (POST /api/rooms).
     */
    public function testCreateRoomsWithSameName()
    {
      $user = factory(User::class)->create();

      $this->assertTrue(Room::count() == 0);
      $response = $this->withHeaders([
          'Authorization' => 'Bearer '.$user->api_token
      ])->json('POST','/api/rooms', [
          'name' => 'Test Room'
      ]);
      $response->assertOk();
      $response = $this->withHeaders([
          'Authorization' => 'Bearer '.$user->api_token
      ])->json('POST','/api/rooms', [
          'name' => 'Test Room'
      ]);
      $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * Test creating a room with authentication (POST /api/rooms).
     */
    public function testCreateRoomsWithAuthentication()
    {
        $user = factory(User::class)->create();

        $this->assertTrue(Room::count() == 0);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST','/api/rooms', [
            'name' => 'Test Room'
        ]);
        $response->assertOk();
        $this->assertTrue(Room::count() == 1);

        $room = Room::first();
        $this->assertEquals('Test Room', $room->name);
        $this->assertEquals('', $room->password);
    }

    /**
     * Test creating a room with a password (POST /api/rooms).
     */
    public function testCreateRoomsWithPassword()
    {
        $user = factory(User::class)->create();

        $this->assertTrue(Room::count() == 0);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST','/api/rooms', [
            'name' => 'Test Room',
            'password' => 'Test Password'
        ]);
        $response->assertOk();
        $this->assertTrue(Room::count() == 1);

        $room = Room::first();
        $this->assertEquals('Test Room', $room->name);
        $this->assertTrue(Hash::check('Test Password', $room->password));
    }
}
