<?php namespace App\Http\Controllers\Employee;

use App\Exceptions\RetrospectiveException;
use App\Http\Controllers\Controller;
use App\Retrospective;
use App\User;
use Auth;
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
     * @return array
     */
    public function timeLeft(Retrospective $retrospective)
    {
        $this->middleware('throttle:180,1');

        $currentPhase = 'submitting';

        if (!$retrospective->voting_starts_at || $retrospective->voting_starts_at && $retrospective->voting_starts_at->isPast())
        {
            $currentPhase = 'voting';
            $votingStartsIn = 0;
        }
        else
        {
            $votingStartsIn = $retrospective->voting_starts_at->diffInSeconds(now());
        }

        if (!$retrospective->expires_at || $retrospective->expires_at && $retrospective->expires_at->isPast())
        {
            $currentPhase = 'done';
            $expiresIn = 0;
        }
        else
        {
            $expiresIn = $retrospective->expires_at->diffInSeconds(now());
        }


        return [
            'current_phase' => $currentPhase,
            'voting_starts_in' => $votingStartsIn,
            'expires_in' => $expiresIn,
        ];
    }

    /**
     * This endpoint will not be used in production, is only used for testing!
     *
     * @param Retrospective $retrospective
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param $invitationCode
     *
     */
    public function join(Retrospective $retrospective)
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
     * Replaces join() when going live
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function acceptInvite(Retrospective $retrospective)
    {
        // Old code
        // /* @var Invite $invite */
        // if (!$invite = Invite::where('token', $token)->first()) {
        //     //if the invite doesn't exist do something more graceful than this
        //     abort(404);
        // }

        if ($retrospective->starts_at->isPast()) {
            throw new RetrospectiveException("The retrospective session has already been started.");
        }

        $user = Auth::user();

        $player = $user->players()->where('retrospective_id', $retrospective->id)->first();

        if (!$player->invites()->first()) {
            throw new RetrospectiveException("You have not been invited to join this retrospective.");
        }

        $player->joined_at = now();

        $player->save();

        return response()->json([
            'message' => 'Player joined the game!',
            'player' => $player,
        ]);
    }
}