<?php

namespace Tests\Unit;

use App\Action;
use App\Http\Controllers\Employee\ActionController;
use App\Player;
use App\Retrospective;
use App\Team;
use App\User;
use App\Vote;
use Tests\TestCase;

class ObserverTest extends TestCase
{
    public function testCascadingDeleting()
    {
        // Tables should be clean
        $this->assertCount(0, Action::all(), Action::class);
        $this->assertCount(0, Player::all(), Player::class);
        $this->assertCount(0, Retrospective::all(), Retrospective::class);
        $this->assertCount(0, User::all(), User::class);
        $this->assertCount(0, Team::all(), Team::class);

        $vote = factory(Vote::class)->create();

        $this->assertCount(1, Action::all(), Action::class);
        $this->assertCount(2, Player::all(), Player::class);
        $this->assertCount(2, Retrospective::all(), Retrospective::class);
        $this->assertCount(4, User::all(), User::class);
        $this->assertCount(0, Team::all(), Team::class);

        // Should delete vote, action, player, retrospective aswell!
        $highestLevelRelationship = $vote->action->player->retrospective->host;

        $highestLevelRelationship->delete();

        // Check to see if they were deleted here
        $this->assertCount(0, Action::all(), Action::class);
        $this->assertCount(1, Player::all(), Player::class);
        $this->assertCount(1, Retrospective::all(), Retrospective::class);
        $this->assertCount(3, User::all(), User::class);
        $this->assertCount(0, Team::all(), Team::class);
    }
}
