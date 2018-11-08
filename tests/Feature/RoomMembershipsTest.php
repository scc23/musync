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

class RoomMembershipsTest extends TestCase
{
    use RefreshDatabase;

    private function createRoomWithPassword($password)
    {
        return factory(Room::class)->create([
            'password' => Hash::make($password)
        ]);
    }

    /**
     * Test that a RoomMembership is created when a Room is created.
     *
     * @return void
     */
    public function testRoomMembershipCreatedWithRoom()
    {
        $user = factory(User::class)->create();

        $this->assertTrue(RoomMembership::count() == 0);
        $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST', '/api/rooms', [
            'name' => 'Test Room'
        ]);
        $this->assertTrue(RoomMembership::count() == 1);

        $room = Room::first();
        $room_membership = RoomMembership::first();
        $this->assertEquals($room->id, $room_membership->room_id);
        $this->assertEquals($user->id, $room_membership->user_id);
    }

    /**
     * Test that a user who created a Room is authorized for that room.
     */
    public function testRoomMembershipAuthenticated()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST', '/api/rooms', [
            'name' => 'Test Room',
            'password' => 'Test Password'
        ]);

        $room = Room::first();
        $response = $this->actingAs($user)
                         ->call('GET', '/room/'.$room->id);
        $response->assertOk();
    }

    /**
     * Test that a user who has not joined a given private room is not authorized.
     */
    public function testRoomMembershipNotAuthorized()
    {
        $room = $this->createRoomWithPassword('Test Password');
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                         ->call('GET', '/room/'.$room->id);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test that a user who has not joined a public room can directly fetch
     * the room via the direct URL (i.e., GET /room/{id}).
     */
    public function testGetPublicRoom()
    {
        $room = factory(Room::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                         ->call('GET', '/room/'.$room->id);
        $response->assertOk();
        $this->assertDatabaseHas('room_memberships',
            ['room_id' => $room->id, 'user_id' => $user->id]);
    }

    /**
     * Test that a user who has joined a private room can directly fetch
     * the room via the direct URL (i.e., GET /room/{id}).
     */
    public function testGetPrivateRoom()
    {
        $room = $this->createRoomWithPassword('Test Password');
        $user = factory(User::class)->create();
        $room_membership = factory(RoomMembership::class)->create([
            'room_id' => $room->id, 'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)
                         ->call('GET', '/room/'.$room->id);
        $response->assertOk();
        $this->assertDatabaseHas('room_memberships',
            ['room_id' => $room->id, 'user_id' => $user->id]);
    }

    /**
     * Test that a user who has not joined a public room is authorized.
     */
    public function testJoinPublicRoom()
    {
        $room = factory(Room::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST', '/api/room/'.$room->id.'/membership', [
            'name' => 'Test Room',
        ]);
        $response->assertOk();
        $this->assertDatabaseHas('room_memberships',
            ['room_id' => $room->id, 'user_id' => $user->id]);
    }

    /**
     * Test that a user who has not joined a private room is not authorized.
     */
    public function testJoinPrivateRoom() {
        $room = $this->createRoomWithPassword('Test Password');
        $user = factory(User::class)->create();

        $room = Room::first();
        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST', '/api/room/'.$room->id.'/membership', [
            'password' => 'Test Password'
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('room_memberships',
            ['room_id' => $room->id, 'user_id' => $user->id]);
    }

    /**
     * Test that a user cannot join a private room without entering the valid
     * password first (i.e., receives HTTP_UNAUTHORIZED after entering an
     * invalid password).
     */
    public function testJoinPrivateRoomWithWrongPassword() {
        $room = $this->createRoomWithPassword('Test Password');
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST', '/api/room/'.$room->id.'/membership', [
            'name' => 'Test Room',
            'password' => 'Wrong Password'
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
