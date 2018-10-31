<?php

use Faker\Generator as Faker;

$factory->define(\App\Player::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory(\App\User::class)->create()->id;
         },
        'retrospective_id' => function() {
            return factory(\App\Retrospective::class)->create()->id;
         },
        'joined_at' => null,
    ];
});
