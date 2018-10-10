<?php

namespace Tests\Unit;

use App\Room;
use App\Http\Controllers\RoomController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test room creation from controller.
     *
     * @return void
     */
    public function testRoomCreate()
    {
        $this->assertTrue(Room::count() == 0);
        $room_controller = new RoomController();
        $room_controller->create();
        $this->assertTrue(Room::count() == 1);
    }
}
