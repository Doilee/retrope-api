<?php

namespace App\Observers;

use App\Player;
use App\Retrospective;

class RetrospectiveObserver
{
    public function deleting(Retrospective $retrospective)
    {
        $retrospective->players->each(function(Player $player) {
            $player->delete();
        });
    }
}
