<?php

namespace Tests\Unit;

use App\Subscription;
use App\Vote;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    /**
     * Tests if factories automatically make much needed relationships
     */
    public function testRelationships()
    {
        $vote = factory(Vote::class)->create();

        $this->assertNotNull($vote->player);
        $this->assertNotNull($vote->player->user);
        $this->assertNotNull($vote->player->retrospective);
        $this->assertNotNull($vote->action);
        $this->assertNotNull($vote->action->player);
        $this->assertNotNull($vote->action->player->user);
        $this->assertNotNull($vote->action->player->retrospective);
        $this->assertNotNull($vote->action->player->retrospective->host);

        $this->assertNotEquals($vote->player, $vote->action->player);

        // clients are not automatically made from each user creation, since this field can be nullable

        $subscription = factory(Subscription::class)->create();

        $this->assertNotNull($subscription->client);
    }
}
