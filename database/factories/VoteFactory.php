<?php

use Faker\Generator as Faker;

$factory->define(\App\Vote::class, function (Faker $faker) {
    return [
        'player_id' => function() {
            return factory(\App\Player::class)->create()->id;
         },
        'action_id' => function() {
            return factory(\App\Action::class)->create()->id;
         },
        'value' => 1
    ];
});
