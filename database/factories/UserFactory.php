<?php

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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'client_id' => function() {
            return factory(\App\Client::class)->create()->id;
         },
        'email' => $faker->unique()->safeEmail,
        'password' => password_hash('secret', PASSWORD_DEFAULT),
        'email_verified_at' => now()->subDay(),
    ];
});
