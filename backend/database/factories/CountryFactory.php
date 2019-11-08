<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Country;
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

$factory->define(Country::class, function (Faker $faker) 
{
  $country_code = $faker->countryCode;
  $country_name = $faker->country;
  $countries = [
      'country_code' => $country_code,
      'country_name' => $country_name,
  ];
  return $countries;
});
