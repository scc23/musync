<?php

namespace Tests\Feature;

use App\Room;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class RoomMessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Send a valid message.
     */
    public function testSendMessage()
    {
        $room = factory(Room::class)->create();
        $user = factory(User::class)->create();
        $room_membership = TestHelper::createMembership($room, $user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST', '/api/room/'.$room->id.'/message', [
            'message' => 'Test Message'
        ]);

        $response->assertOk();
    }

    /**
     * Attempt to send no message.
     */
    public function testSendNoMessage()
    {
        $room = factory(Room::class)->create();
        $user = factory(User::class)->create();
        $room_membership = TestHelper::createMembership($room, $user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST', '/api/room/'.$room->id.'/message');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * Attempt to send an invalid (empty) message.
     */
    public function testSendEmptyMessage()
    {
        $room = factory(Room::class)->create();
        $user = factory(User::class)->create();
        $room_membership = TestHelper::createMembership($room, $user);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer '.$user->api_token
        ])->json('POST', '/api/room/'.$room->id.'/message', [
            'message' => ''
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
