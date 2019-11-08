<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\State;
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

$factory->define(State::class, function (Faker $faker) 
{
  $states_code = $faker->state;
  $states_name = $faker->state;
  $states = [
      'states_code' => $states_code[2],
      'states_name' => $states_name,
  ];
  return $states;
});
