<?php

namespace Tests\Feature;

use App\Room;
use App\RoomBroadcaster;
use App\RoomMembership;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class RoomBroadcastTest extends TestCase
{
    use RefreshDatabase;

    private function createBroadcaster($room, $user)
    {
        return factory(RoomBroadcaster::class)->create([
            'room_id' => $room->id, 'user_id' => $user->id
        ]);
    }

    /**
     * Attempt to retrieve the broadcaster when none is set.
     */
    public function testGetNonExistentBroadcaster()
    {
        $room = factory(Room::class)->create();
        $user = factory(User::class)->create();
        $room_membership = TestHelper::createMembership($room, $user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('GET', '/api/room/'.$room->id.'/broadcast');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * Retrieve the broadcaster of a room.
     */
    public function testGetBroadcaster()
    {
        $room = factory(Room::class)->create();
        $user = factory(User::class)->create();
        $room_membership = TestHelper::createMembership($room, $user);
        $room_broadcaster = $this->createBroadcaster($room, $user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('GET', '/api/room/'.$room->id.'/broadcast');

        $response->assertOk()
                 ->assertJson([
                    'room_id' => $room->id,
                    'user_id' => $user->id
                 ]);
    }

    /**
     * Begin broadcasting in a room without a broadcaster.
     */
    public function testBeginBroadcast()
    {
        $room = factory(Room::class)->create();
        $user = factory(User::class)->create();
        TestHelper::createMembership($room, $user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST', '/api/room/'.$room->id.'/broadcast');

        $response->assertOk()
                 ->assertJson([
                    'room_id' => $room->id,
                    'user_id' => $user->id
                 ]);
        $this->assertDatabaseHas('room_broadcasters',
            ['room_id' => $room->id, 'user_id' => $user->id]);
    }

    /**
     * Attempt to become a broadcaster for a room that already has one.
     */
    public function testBeginBroadcastWithExistingBroadcaster()
    {
      $room = factory(Room::class)->create();
      $user1 = factory(User::class)->create();
      TestHelper::createMembership($room, $user1);
      $this->createBroadcaster($room, $user1);

      $user2 = factory(User::class)->create();
      TestHelper::createMembership($room, $user2);

      $response = $this->actingAs($user2)->withHeaders([
          'Authorization' => 'Bearer '.$user2->api_token
      ])->json('POST', '/api/room/'.$room->id.'/broadcast');

      $response->assertStatus(Response::HTTP_BAD_REQUEST);
      $this->assertDatabaseMissing('room_broadcasters',
          ['room_id' => $room->id, 'user_id' => $user2->id]);
    }

    /**
     * Stop broadcasting as the broadcaster.
     */
    public function testStopBroadcasting()
    {
        $room = factory(Room::class)->create();
        $user = factory(User::class)->create();
        TestHelper::createMembership($room, $user);
        $this->createBroadcaster($room, $user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('DELETE', '/api/room/'.$room->id.'/broadcast');

        $response->assertOk();
        $this->assertDatabaseMissing('room_broadcasters',
            ['room_id' => $room->id, 'user_id' => $user->id]);
    }

    /**
     * Attempt to stop broadcasting in a room without a broadcaster.
     */
    public function testStopBroadcastingWithNoBroadcaster()
    {
        $room = factory(Room::class)->create();
        $user = factory(User::class)->create();
        TestHelper::createMembership($room, $user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('DELETE', '/api/room/'.$room->id.'/broadcast');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $this->assertDatabaseMissing('room_broadcasters',
            ['room_id' => $room->id, 'user_id' => $user->id]);
    }

    /**
     * Attempt to stop broadcasting as a non-broadcaster.
     */
    public function testStopBroadcastingAsNonBroadcaster()
    {
        $room = factory(Room::class)->create();
        $user1 = factory(User::class)->create();
        TestHelper::createMembership($room, $user1);
        $this->createBroadcaster($room, $user1);

        $user2 = factory(User::class)->create();
        TestHelper::createMembership($room, $user2);

        $response = $this->actingAs($user2)->withHeaders([
            'Authorization' => 'Bearer '.$user2->api_token
        ])->json('DELETE', '/api/room/'.$room->id.'/broadcast');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $this->assertDatabaseHas('room_broadcasters',
            ['room_id' => $room->id, 'user_id' => $user1->id]);
        $this->assertDatabaseMissing('room_broadcasters',
            ['room_id' => $room->id, 'user_id' => $user2->id]);
    }
}
