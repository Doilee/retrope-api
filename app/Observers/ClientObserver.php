<?php

namespace App\Observers;

use App\Client;
use App\Subscription;

class ClientObserver
{
    public function deleting(Client $client)
    {
        $client->subscriptions->each(function(Subscription $subscription) {
            $subscription->delete();
        });

        $client->users->each(function(Subscription $subscription) {
            $subscription->delete();
        });
    }
}
