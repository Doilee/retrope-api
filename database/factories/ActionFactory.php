<?php

use Faker\Generator as Faker;

$factory->define(\App\Action::class, function (Faker $faker) {
    return [
        'player_id' => function() {
            return factory(\App\Player::class)->create()->id;
         },
        'feedback' => $faker->text,
    ];
});
