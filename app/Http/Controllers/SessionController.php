<?php namespace App\Http\Controllers;

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
     * @param $invitationCode
     */
    public function show($invitationCode)
    {
        return $this->sessionFromCode($invitationCode);
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

        $invitationCode = str_random();

        $session = $host->session()->create([
            'name' => $request->get('name'),
            'invitation_code' => $invitationCode
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

    public function scheduleInvitation(Request $request, Session $session)
    {
        $this->validate($request, [
            'scheduled_at' => 'required|date|after:' . now()->toDateTimeString()
        ]);

        $invitation = $session->invitations()->where('scheduled_at', $request->get('scheduled_at'))->first() ??
            $session->invitations()->create([
                'scheduled_at' => $request->get('scheduled_at')
            ]);

        return response()->json([
            'message' => 'Invitation scheduled.',
            'invitation' => $invitation
        ]);
    }

    /**
     * @param string $invitationCode
     *
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    private function sessionFromCode(string $invitationCode)
    {
        $session = Session::where('invitation_code', $invitationCode)->firstOrFail();

        return $session;
    }
}