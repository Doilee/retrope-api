<?php

use Faker\Generator as Faker;

$factory->define(App\Team::class, function (Faker $faker) {
    return [
        'name' => 'Team ' . $faker->colorName,
        'client_id' => function() {
            return factory(\App\Client::class)->create()->id;
         },
    ];
});
