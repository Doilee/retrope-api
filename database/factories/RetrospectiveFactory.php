<?php

use Faker\Generator as Faker;

$factory->define(\App\Retrospective::class, function (Faker $faker) {
    return [
        'host_id' => function() {
            return factory(\App\User::class)->create()->id;
         },
        'name' => $faker->name,
        'scheduled_at' => null,
        'starts_at' => null,
        'voting_starts_at' => null,
        'expires_at' => null
    ];
});
