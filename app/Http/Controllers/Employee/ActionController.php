<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Player;
use App\Action;
use App\Retrospective;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class RetrospectiveController
 * @package App\Http\Controllers
 */
class ActionController extends Controller
{
    /* @var Player $player */
    protected $player;

    public function show(Action $action)
    {
        return $action;
    }

    /**
     * Create a retrospective
     *
     * @param Request $request
     * @param Retrospective $retrospective
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, Retrospective $retrospective)
    {
        $this->validatePlayer($retrospective);

        $this->validate($request, [
            'feedback' => 'required|string'
        ]);

        if ($retrospective->isExpired())
        {
            return response()->json([
                'message' => 'Retrospective expired',
            ], 410);
        }

        $action = $this->player->actions()->create([
            'feedback' => $request->get('feedback')
        ]);

        return response()->json([
            'message' => 'Success!',
            'action' => $action
        ], 201);
    }

    /**
     * Update the contents of a retrospective
     *
     * @param Request $request
     * @param Action $action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Action $action)
    {
        $this->validatePlayer($action->player->retrospective);

        $this->validate($request, [
            'feedback' => 'required|string'
        ]);

        if ($action->player->retrospective->isExpired())
        {
            return response()->json([
                'message' => 'Retrospective expired',
            ], 410);
        }

        $action->update([
            'feedback' => $request->get('feedback'),
        ]);

        return response()->json([
            'message' => 'Success!'
        ], 201);
    }

    /**
     * Vote for any given action as a player, you have a maximum of 5 votes to hand out.
     * @param Action $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Action $action)
    {
        $this->validatePlayer($action->player->retrospective);

        $votesGiven = $this->player->likes()->count();

        if ($votesGiven >= Vote::MAXIMUM_PER_PLAYER) {
            throw new BadRequestHttpException('Maximum votes of 5 have been exhausted by the player.');
        }

        $vote = $this->player->vote($action);

        return response()->json([
            'message' => 'One vote has been given.',
            'vote' => $vote,
            'votesLeft' => Vote::MAXIMUM_PER_PLAYER - ($votesGiven + 1)
        ], 201);
    }

    /**
     * Remove a vote for any given action as a player, you have a maximum of 5 votes to hand out.
     *
     * @param Vote $vote
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function removeVote(Vote $vote)
    {
        $this->validatePlayer($vote->player->retrospective);

        $vote->delete();

        return response()->json([
            'message' => 'Vote has deleted.'
        ], 200);
    }

    /**
     * Check if the player is a part of the retrospective
     *
     * @param Retrospective $retrospective
     */
    private function validatePlayer(Retrospective $retrospective)
    {
        $this->player = $retrospective->players()->where('user_id', Auth::user()->id)->firstOrFail();
    }
}
