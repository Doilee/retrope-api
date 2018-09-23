<?php

namespace App\Http\Controllers;

use App\Player;
use App\Retrospective;
use App\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

/**
 * Class RetrospectiveController
 * @package App\Http\Controllers
 */
class RetrospectiveController extends Controller
{
    /* @var Player $player */
    protected $player;

    /**
     * Create a retrospective
     *
     * @param Request $request
     * @param $invitationCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, $invitationCode)
    {
        $session = Session::where('invitation_code', $invitationCode)->firstOrFail();

        $this->validatePlayer($session);

        $this->validate($request, [
            'feedback' => 'required|string'
        ]);

        if ($session->isExpired())
        {
            return response()->json([
                'message' => 'Session expired',
            ], 410);
        }

        $this->player->retrospectives()->create([
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
     * @param $invitationCode
     * @param Retrospective $retrospective
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $invitationCode, Retrospective $retrospective)
    {
        $session = Session::where('invitation_code', $invitationCode)->firstOrFail();

        $this->validatePlayer($session);

        $this->validate($request, [
            'feedback' => 'required|string'
        ]);

        if ($session->isExpired())
        {
            return response()->json([
                'message' => 'Session expired',
            ], 410);
        }

        $retrospective->update([
            'feedback' => $request->get('feedback'),
        ]);

        return response()->json([
            'message' => 'Success!'
        ], 201);
    }

    /**
     * Toggle like on a retrospective
     *
     * @param Retrospective $retrospective
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Retrospective $retrospective)
    {
        $this->validatePlayer($retrospective->player->session);

        $added = $this->player->like($retrospective);

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
     * @param Retrospective $retrospective
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dislike(Retrospective $retrospective)
    {
        $this->validatePlayer($retrospective->player->session);

        $added = $this->player->dislike($retrospective);

        if ($added) {
            return response()->json([
                'message' => 'Disliked.'
            ], 201);
        }

        return response()->json([
                'message' => 'Removed dislike.'
            ], 200);
    }

    /**
     * Check if the player is a part of the session
     *
     * @param $session
     */
    private function validatePlayer($session)
    {
        $this->player = $session->players()->where('user_id', Auth::user()->id)->firstOrFail();
    }
}
