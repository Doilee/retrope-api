<?php

namespace App\Observers;

use App\Action;
use App\Player;
use App\User;
use App\Vote;

class PlayerObserver
{
    public function deleting(Player $player)
    {
        $player->actions->each(function(Action $action) {
            $action->delete();
        });

        $player->votes->each(function(Vote $vote) {
            $vote->delete();
        });
    }
}
