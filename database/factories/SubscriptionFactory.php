<?php

use Faker\Generator as Faker;

$factory->define(\App\Subscription::class, function (Faker $faker) {
    return [
        'client_id' => function() {
            return factory(\App\Client::class)->create()->id;
         },
        'type' => $faker->randomElement(['trial', 'pro']),
        'expires_at' => now()->addYear()->toDateTimeString()
    ];
});
