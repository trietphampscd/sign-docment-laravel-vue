<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Department;
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

$factory->define(Department::class, function (Faker $faker) 
{
    $department_name = $faker->company;
    $department = [
        'department_name' => $department_name,
    ];
    return $department;
});
