<?php

use Faker\Generator as Faker;

$factory->define(\App\Subscription::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['trial', 'pro']),
        'expires_at' => now()->addYear()->toDateTimeString()
    ];
});
