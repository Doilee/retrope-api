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
        $action = factory(Action::class)->create();
    }
}
