<?php

namespace Tests\Unit;

use App\Action;
use App\Player;
use App\Retrospective;
use App\User;
use App\Vote;
use Tests\TestCase;

class ObserverTest extends TestCase
{
    public function testCascadingDeleting()
    {
        // Tables should be clean
        $this->assertCount(0, Action::all());
        $this->assertCount(0, Player::all());
        $this->assertCount(0, Retrospective::all());
        $this->assertCount(0, User::all());

        $vote = factory(Vote::class)->create();

        $this->assertCount(1, Action::all());
        $this->assertCount(2, Player::all());
        $this->assertCount(2, Retrospective::all());
        $this->assertCount(4, User::all());

        // Should delete vote, action, player, retrospective aswell!
        $highestLevelRelationship = $vote->action->player->retrospective->host;

        $highestLevelRelationship->delete();

        // Check to see if they were deleted here
        $this->assertCount(0, Action::all());
        $this->assertCount(1, Player::all());
        $this->assertCount(1, Retrospective::all());
        $this->assertCount(3, User::all());
    }
}
