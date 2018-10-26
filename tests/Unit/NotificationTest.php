<?php

namespace Tests\Unit;

use App\Client;
use App\Notifications\SubscriptionStarted;
use App\Subscription;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Notifications\Events\NotificationFailed;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testSubscriptionStarted()
    {
        Notification::fake();

        $client = factory(Client::class)->create();

        $subscription = factory(Subscription::class)->create([
            'client_id' => $client->id
        ]);
        /* @var User $user */
        $user = factory(User::class)->create();

        $notification = new SubscriptionStarted($subscription);

        $user->notify($notification);

        Notification::assertSentTo($user, SubscriptionStarted::class);
    }
}
