<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Industries;
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

$factory->define(Industries::class, function (Faker $faker) 
{
    $name = $faker->name;
   
    $industries = [
        'industry_name' => $name
    ];
    return $industries;
});
