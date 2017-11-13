<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'api_token' => $faker->uuid,
    ];
});

$factory->defineAs(App\Models\User::class, 'admin', function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'api_token' => $faker->uuid,
        'admin' => true,
    ];
});

$factory->define(App\Models\Container::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\Models\Media::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});
