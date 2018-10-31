<?php

namespace Tests\Unit;

use App\Action;
use App\Player;
use Tests\TestCase;

class VoteTest extends TestCase
{
    /**
     * @return void
     */
    public function testVote()
    {
        /* @var Action $action */
        $action = factory(Action::class)->create();

        /* @var Player $player */
        $voter = factory(Player::class)->create();

        $vote = $voter->vote($action);

        $this->assertDatabaseHas('votes', [
            'player_id' => $voter->id,
            'action_id' => $action->id,
            'value' => 1
        ]);

        // Vote again

        $vote = $voter->vote($action);

        $this->assertCount(2, $action->votes);
    }
}
