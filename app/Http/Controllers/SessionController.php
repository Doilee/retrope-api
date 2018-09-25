<?php namespace App\Http\Controllers;

use App\invitation;
use App\Mail\ParticipantInvited;
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
     */
    public function show(Session $session)
    {
        return $session;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2'
        ]);

        /** @var User $host */
        $host = auth()->user();

        $session = $host->session()->create([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'message' => 'Success',
            'session' => $session
        ], 201);
    }

    /**
     * @param Request $request
     * @param $invitationCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function participate(Request $request, $invitationCode)
    {
        $user = auth()->user();

        $session = $this->sessionFromCode($invitationCode);

        $player = $user->players()->create([
            'session_id' => $session->id,
        ]);

        return response()->json([
            'message' => 'Player succesfully added to the session!',
            'player' => $player
        ]);
    }

    /**
     * @param Request $request
     * @param Session $session
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invite(Request $request, Session $session)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        Mail::to($request->get('email'))
            ->send(new ParticipantInvited($session));
        // mail to email

        return response()->json([
            'message' => 'Participant invited!'
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

    /**
     * @param string $invitationCode
     *
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    private function sessionFromCode(string $invitationCode)
    {
        $invitation = Invitation::where('code', $invitationCode)->firstOrFail();

        return $invitation->session;
    }
}