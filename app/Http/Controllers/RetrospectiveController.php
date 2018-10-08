<?php namespace App\Http\Controllers;

use App\Retrospective;
use App\User;
use Illuminate\Http\Request;
use Mail;

/**
 * Class RetrospectiveController
 * @package App\Http\Controllers
 */
class RetrospectiveController extends Controller
{
    /**
     * @param Retrospective $retrospective
     *
     * @return Retrospective
     */
    public function show(Retrospective $retrospective)
    {
        $retrospective->load('actions');
        return $retrospective;
    }

    /**
     * @param Retrospective $retrospective
     *
     * @return array
     */
    public function timeLeft(Retrospective $retrospective)
    {
        $this->middleware('throttle:180,1');

        $votingStartsIn = $retrospective->voting_starts_at ? $retrospective->voting_starts_at->diffInSeconds(now()) : null;
        $expires = $retrospective->expires_at ? $retrospective->expires_at->diffInSeconds(now()) : null;

        return [
            'voting_starts_in' => $votingStartsIn,
            'expires_in' => $expires,
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2',
            'scheduled_at' => 'nullable|date|after:' . now()->toDateTimeString(),
            'starts_at' => 'nullable|date|after:' . now()->toDateTimeString(),
        ]);

        /** @var User $host */
        $host = auth()->user();

        $retrospective = $host->retrospective()->create([
            'name' => $request->get('name'),
            'scheduled_at' => $request->get('scheduled_at'),
        ]);

        if ($request->has('starts_at'))
        {
            $retrospective->update([
                'starts_at' => $request->get('starts_at'),
            ]);
        }

        return response()->json([
            'message' => 'Success',
            'retrospective' => $retrospective
        ], 201);
    }

    /**
     * @param Request $request
     * @param Retrospective $retrospective
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param $invitationCode
     *
     */
    public function join(Request $request, Retrospective $retrospective)
    {
        $user = auth()->user();

        if (!$retrospective->is_public) {
            return response()->json([
                'message' => 'Retro is private!',
                'retrospective' => $retrospective
            ]);
        }

        $player = $retrospective->players()->create([
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'User is now ready to play!',
            'player' => $player
        ]);
    }

    /**
     * @param Request $request
     * @param Retrospective $retrospective
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function start(Request $request, Retrospective $retrospective)
    {
        $this->validate($request, [
            'timer' => 'nullable|integer|max:600'
        ]);

        $retrospective->start($request->get('timer'));

        return response()->json([
            'message' => 'Retrospective started.'
        ]);
    }
}