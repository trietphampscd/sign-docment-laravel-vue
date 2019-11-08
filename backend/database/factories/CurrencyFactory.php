<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Currency;
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

$factory->define(Currency::class, function (Faker $faker) 
{
  $currency_code = $faker->currencyCode;
  $currency_name = $faker->currencyCode;
  $currencies = [
      'currency_code' => $currency_code,
      'currency_name' => $currency_name,
  ];
  return $currencies;
});
