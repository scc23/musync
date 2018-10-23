<?php

namespace Tests\Feature;

use App\Room;
use App\RoomMembership;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;


class RoomMembershipsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a RoomMembership is created when a Room is created.
     *
     * @return void
     */
    public function testRoomMembershipCreatedWithRoom()
    {
      $user = factory(User::class)->create();

      $this->assertTrue(RoomMembership::count() == 0);
      $response = $this->actingAs($user)
                       ->call('POST', '/rooms', [
                          'create-room-name' => 'Test Room'
                       ]);
      $this->assertTrue(RoomMembership::count() == 1);

      $room = Room::first();
      $room_membership = RoomMembership::first();
      $this->assertEquals($room->id, $room_membership->room_id);
      $this->assertEquals($user->id, $room_membership->user_id);
    }

    /**
     * Test that a user who created a room is authenticated for that room.
     */
    public function testRoomMembershipAuthenticated() {
      $user = factory(User::class)->create();

      $this->actingAs($user)
           ->call('POST', '/rooms', [
               'create-room-name' => 'Test Room',
               'create-room-password' => 'Test Password'
           ]);

      $room = Room::first();
      $response = $this->actingAs($user)
                       ->call('GET', '/room/'.$room->id);
      $response->assertOk();
    }

    /**
     * Test that a user who has not joined a private room is not authenticated.
     */
    public function testRoomMembershipNotAuthenticated() {
      $user1 = factory(User::class)->create();
      $user2 = factory(User::class)->create();

      $this->actingAs($user1)
           ->call('POST', '/rooms', [
               'create-room-name' => 'Test Room',
               'create-room-password' => 'Test Password'
           ]);

      $room = Room::first();
      $response = $this->actingAs($user2)
                       ->call('GET', '/room/'.$room->id);
      $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test that a user who has not joined a private room is not authenticated.
     */
    public function testJoinPublicRoom() {
      $user1 = factory(User::class)->create();
      $user2 = factory(User::class)->create();

      $this->actingAs($user1)
           ->call('POST', '/rooms', [
              'create-room-name' => 'Test Room'
           ]);

      $room = Room::first();
      $response = $this->actingAs($user2)
                       ->call('POST', '/room/'.$room->id.'/membership');

      $response = $this->actingAs($user2)
                       ->call('GET', '/room/'.$room->id);
      $response->assertOk();
    }

    /**
     * Test that a user who has not joined a private room is not authenticated.
     */
    public function testJoinPrivateRoom() {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user1)
             ->call('POST', '/rooms', [
                'create-room-name' => 'Test Room',
                'create-room-password' => 'Test Password'
             ]);

        $room = Room::first();
        $response = $this->actingAs($user2)
                         ->call('POST', '/room/'.$room->id.'/membership', [
                             'join-room-password' => 'Test Password'
                         ]);

        $response = $this->actingAs($user2)
                         ->call('GET', '/room/'.$room->id);
        $response->assertOk();
    }

    /**
     * Test that a user cannot join a private room without entering the valid
     * password first (i.e., is still HTTP_UNAUTHORIZED after entering an
     * invalid password).
     */
    public function testJoinPrivateRoomWithWrongPassword() {
      $user1 = factory(User::class)->create();
      $user2 = factory(User::class)->create();

      $this->actingAs($user1)
           ->call('POST', '/rooms', [
              'create-room-name' => 'Test Room',
              'create-room-password' => 'Test Password'
           ]);

      $room = Room::first();
      $response = $this->actingAs($user2)
                       ->call('POST', '/room/'.$room->id.'/membership', [
                           'join-room-password' => 'Wrong Password'
                       ]);

      $response = $this->actingAs($user2)
                       ->call('GET', '/room/'.$room->id);
      $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
