<?php
use App\Room;
use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed 3 random rooms
        $rooms = factory(Room::class, 3)->create();
    }
}
