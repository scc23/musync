<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {

    do {
        $id = $faker->unique()->randomNumber($nbDigits = 3, $strict = false);
    } while ( App\User::where('id', $id)->exists() );
    
    return [        
        'id' => $id,
        'spotify_id' => str_random(10),
        'name' => $faker->userName,
        'refresh_token' =>  str_random(10),
        'remember_token' => str_random(10),
        'api_token' => str_random(10)

    ];
});