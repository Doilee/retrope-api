<?php

use Faker\Generator as Faker;

$factory->define(App\Client::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'subscription_expires_at' => now()->addYear()
    ];
});
