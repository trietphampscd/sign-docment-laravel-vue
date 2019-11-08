<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\CompanySize;
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

$factory->define(CompanySize::class, function (Faker $faker) 
{
    $name = ['startup','enterprise'];
    $number = rand(0,1);
    $company_size_name = $name[$number];
    $size_from = rand(1,100);
    $size_to = rand($size_from, 1000);
   
    $company_size = [
        'company_size_name' => $company_size_name,
        'size_from' => $size_from,
        'size_to' => $size_to
    ];
    return $company_size;
});
