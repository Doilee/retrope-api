<?php

namespace Tests\Unit;

use App\Client;
use App\Console\Commands\SendScheduledInvitations;
use App\Notifications\PlayerInvited;
use App\Notifications\SubscriptionStarted;
use App\Player;
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
    public function setUp()
    {
        parent::setUp();

        Notification::fake();
    }

    public function testSubscriptionStarted()
    {
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

        /**
     * A basic test example.
     *
     * @return void
     */
    public function testSendingScheduledInvitations()
    {
        /* @var Player $player */
        $player = factory(Player::class)->create();

        $retrospective = $player->retrospective;

        $retrospective->scheduled_at = now()->addHour()->toDateTimeString();

        $retrospective->save();

        $command = new SendScheduledInvitations;

        $command->handle();

        Notification::assertNothingSent();

        $retrospective->scheduled_at = now()->subHour()->toDateTimeString();

        $retrospective->save();

        $command->handle();

        $notification = PlayerInvited::class;

        Notification::assertSentTo($player->user, $notification);
        Notification::assertTimesSent(1, $notification);
    }
}
