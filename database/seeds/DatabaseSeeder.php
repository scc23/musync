<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Clean the current database
        $this->command->call('migrate:fresh');
        
        // It is important that the seeders are called in this particular order
        // as RoomMemberships and Messages rely on Rooms and Users
        $this->call([
            UsersTableSeeder::class,
            RoomsTableSeeder::class,
            RoomMembershipsTableSeeder::class,
            MessagesTableSeeder::class,
        ]);
    }
}
