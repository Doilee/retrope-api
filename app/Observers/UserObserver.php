<?php

namespace App\Observers;

use App\Player;
use App\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserObserver
{
    public function creating(User $user)
    {
        // if (!$user->email) $user->email = uniqid();
    }

    public function deleting(User $user)
    {
        $user->players->each(function(Player $player) {
            $player->delete();
        });
    }
}
