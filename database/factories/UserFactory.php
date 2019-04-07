<?php

use App\User;
use Illuminate\Support\Str;
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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'phone' => $faker->phoneNumber,
        'photo' => 'http://via.placeholder.com/150x150',
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];

    // return [
    //     'name' => 'Abedy',
    //     'phone' => '01672791097',
    //     'photo' => 'http://via.placeholder.com/150x150',
    //     'email' => 'abedy.ewu@gmail.com',
    //     'email_verified_at' => now(),
    //     'password' => bcrypt('123'), // password
    //     'remember_token' => Str::random(10),
    // ];

});


$factory->define(App\Message::class, function (Faker $faker) {
    do{
        $from= rand(1,31);
        $to= rand(1,31);
    }while($from === $to);
    
    return [
        'from' => $from,
        'to' => $to,
        'text' => $faker->sentence,
    ];
});

