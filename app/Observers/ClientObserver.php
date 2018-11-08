<?php

namespace App\Observers;

use App\Client;
use App\Subscription;
use App\User;

class ClientObserver
{
    public function deleting(Client $client)
    {
        $client->subscriptions->each(function(Subscription $subscription) {
            $subscription->delete();
        });

        $client->users->each(function(User $user) {
            $user->delete();
        });
    }
}
