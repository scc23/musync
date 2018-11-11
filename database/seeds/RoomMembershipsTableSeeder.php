<?php
use App\RoomMembership;
use Illuminate\Database\Seeder;

class RoomMembershipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 5 random memberships from existing Rooms and Users
        $memberships = factory(App\RoomMembership::class, 5)->create();
    }
}
