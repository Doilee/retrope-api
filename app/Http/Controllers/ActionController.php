<?php

namespace App\Http\Controllers;

use App\Player;
use App\Action;
use App\Retrospective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

/**
 * Class RetrospectiveController
 * @package App\Http\Controllers
 */
class ActionController extends Controller
{
    /* @var Player $player */
    protected $player;

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

        $this->player->actions()->create([
            'feedback' => $request->get('feedback')
        ]);

        return response()->json([
            'message' => 'Success!'
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
     * Toggle like on a retrospective
     *
     * @param Action $action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Action $action)
    {
        $this->validatePlayer($action->player->retrospective);

        $added = $this->player->like($action);

        if ($added) {
            return response()->json([
                'message' => 'Liked!'
            ], 201);
        }

        return response()->json([
                'message' => 'Removed like.'
            ], 200);
    }

    /**
     * Toggle dislike on a retrospective
     *
     * @param Action $action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dislike(Action $action)
    {
        $this->validatePlayer($action->player->retrospective);

        $added = $this->player->dislike($action);

        if ($added) {
            return response()->json([
                'message' => 'Disliked.'
            ], 201);
        }

        return response()->json([
                'message' => 'Removed dislike.'
            ], 200);
    }

    public function vote(Action $action)
    {
        $this->validatePlayer($action->player->retrospective);

        $vote = $this->player->vote($action);

        $vote->save();

        return response()->json([
            'message' => 'One vote has been given.'
        ], 201);
    }

    /**
     * Check if the player is a part of the retrospective
     *
     * @param $action
     */
    private function validatePlayer($action)
    {
        $this->player = $action->players()->where('user_id', Auth::user()->id)->firstOrFail();
    }
}
