<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
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

$factory->define(User::class, function (Faker $faker) 
{
    $email = $faker->email;
    $name = $faker->name;
    $password = Hash::make('Pass123!');
    $role = 1;
    $user = [
        'email' => $faker->unique()->safeEmail,
        'password' => $password,
        'name' => $name,
        'admin' => $role,
        'active' => 1
    ];
    return $user;
});
