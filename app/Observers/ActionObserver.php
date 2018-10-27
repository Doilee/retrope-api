<?php

namespace App\Observers;

use App\Action;
use App\Vote;

class ActionObserver
{
    public function deleting(Action $action)
    {
        $action->votes->each(function(Vote $vote) {
            $vote->delete();
        });
    }
}
