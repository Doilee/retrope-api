<?php namespace App\Http\Controllers;

use App\Invite;
use App\Mail\PlayerInvited;
use App\Session;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

/**
 * Class SessionController
 * @package App\Http\Controllers
 */
class SessionController extends Controller
{
    /**
     * @param Session $session
     *
     * @return Session
     */
    public function show(Session $session)
    {
        return $session;
    }

    /**
     * @param Session $session
     *
     * @return array
     */
    public function timeLeft(Session $session)
    {
        $this->middleware('throttle:180,1');

        $currentPhase = 'submitting';

        if (!$session->voting_starts_at || $session->voting_starts_at && $session->voting_starts_at->isPast())
        {
            $currentPhase = 'voting';
            $votingStartsIn = 0;
        }
        else
        {
            $votingStartsIn = $session->voting_starts_at->diffInSeconds(now());
        }

        if (!$session->expires_at || $session->expires_at && $session->expires_at->isPast())
        {
            $currentPhase = 'done';
            $expiresIn = 0;
        }
        else
        {
            $expiresIn = $session->expires_at->diffInSeconds(now());
        }


        return [
            'current_phase' => $currentPhase,
            'voting_starts_in' => $votingStartsIn,
            'expires_in' => $expiresIn,
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
            'timer' => 'nullable|integer|max:600'
        ]);

        /** @var User $host */
        $host = auth()->user();

        $session = $host->session()->create([
            'name' => $request->get('name'),
            'scheduled_at' => $request->get('scheduled_at'),
        ]);

        if ($request->has('timer'))
        {
            $session->update([
                'starts_at' => now()->addSeconds($request->get('timer') ?? 0)->toDateTimeString(),
            ]);
        }

        return response()->json([
            'message' => 'Success',
            'session' => $session
        ], 201);
    }

    /**
     * @param Request $request
     * @param Session $session
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param $invitationCode
     *
     */
    public function join(Request $request, Session $session)
    {
        $user = auth()->user();

        $player = $session->players()->create([
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'User is now ready to play!',
            'player' => $player
        ]);
    }

    /**
     * @param Request $request
     * @param Session $session
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function start(Request $request, Session $session)
    {
        $this->validate($request, [
            'timer' => 'nullable|integer|max:600'
        ]);

        $session->start($request->get('timer'));

        return response()->json([
            'message' => 'Session started.'
        ]);
    }
}