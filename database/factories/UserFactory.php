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

$factory->define(User::class, function (Faker $faker) {
    return [
        'user_id'=>$faker->numerify('00##'),
        'name' => $faker->name,
        'sex'=>$faker->randomElements(['男','女']),
        'phone_number'=>$faker->numerify('13847######'),
        'email' => $faker->unique()->safeEmail,
        'unit_id'=>$faker->numberBetween(0, 5),
        'role_id'=>$faker->numberBetween(0, 5),
        'parent_id'=>$faker->numberBetween(0, 5),
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
