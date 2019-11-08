<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Language;
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

$factory->define(Language::class, function (Faker $faker) 
{
  $language_code = $faker->languageCode;
  $language_name = $faker->languageCode;
  $languages = [
      'language_code' => $language_code,
      'language_name' => $language_name,
  ];
  return $languages;
});
