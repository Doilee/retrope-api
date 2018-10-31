<?php

namespace App\Observers;

use App\Player;
use App\Retrospective;
use App\User;

class UserObserver
{
    public function creating(User $user)
    {
        // if (!$user->email) $user->email = uniqid();
    }

    public function deleting(User $user)
    {
        $user->retrospectives->each(function(Retrospective $retrospective) {
            $retrospective->delete();
        });

        $user->players->each(function(Player $player) {
            $player->delete();
        });
    }
}
