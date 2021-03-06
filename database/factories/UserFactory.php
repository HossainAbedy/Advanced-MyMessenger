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
        'avatar' => 'default.jpg',
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$nJ4L/uZzsW.ku.fAjHboC.Dv8zeBqKR4WC01Aa/U8KbJ4Fl5zhEcO', // password
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

    // return [
    //     'name' => 'Abedin',
    //     'phone' => '01672791097',
    //     'photo' => 'http://via.placeholder.com/150x150',
    //     'email' => 'abedin.ewu@gmail.com',
    //     'email_verified_at' => now(),
    //     'password' => bcrypt('123'), // password
    //     'remember_token' => Str::random(10),
    // ];

    // return [
    //     'name' => 'Abedi',
    //     'phone' => '01672791097',
    //     'photo' => 'http://via.placeholder.com/150x150',
    //     'email' => 'abedi.ewu@gmail.com',
    //     'email_verified_at' => now(),
    //     'password' => bcrypt('123'), // password
    //     'remember_token' => Str::random(10),
    // ];

});


$factory->define(App\Message::class, function (Faker $faker) {
    do{
        $from= rand(1,33);
        $to= rand(1,33);
    }while($from === $to);
    
    return [
        'from' => $from,
        'to' => $to,
        'text' => $faker->sentence,
    ];
});

