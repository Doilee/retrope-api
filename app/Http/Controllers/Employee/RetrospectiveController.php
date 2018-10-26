<?php namespace App\Http\Controllers\Employee;

use App\Exceptions\RetrospectiveException;
use App\Http\Controllers\Controller;
use App\Retrospective;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Mail;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * Replaces join() when going live
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function join(Retrospective $retrospective)
    {
        if ($retrospective->hasStarted()) {
            throw new BadRequestHttpException("The retrospective session has already been started.");
        }

        $user = Auth::user();

        $player = $user->players()->where('retrospective_id', $retrospective->id)->first();

        if (!$player) {
            throw new RetrospectiveException("You have not been invited to join this retrospective.");
        }

        $player->joined_at = now()->toDateTimeString();

        $player->save();

        return response()->json([
            'message' => 'Player joined the game!',
            'player' => $player,
        ]);
    }
}