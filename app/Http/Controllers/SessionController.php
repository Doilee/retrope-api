<?php namespace App\Http\Controllers;

use App\Session;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function show($invitationCode)
    {
        $session = Session::where('invitation_code', $invitationCode)->firstOrFail();

        return $session;
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2',
            'timer' => 'nullable|integer|min:0|max:600',
        ]);

        /** @var User $host */
        $host = auth()->user();

        $invitationCode = str_random();

        $host->session()->create([
            'name' => $request->get('name'),
            'invitation_code' => $invitationCode,
            'started_at' => now(),
            'expires_at' => now()->addSeconds($request->get('timer') ?? 0),
        ]);

        return response()->json([
            'message' => 'Success',
            'invitation_code' => $invitationCode
        ], 201);
    }

    public function participate(Request $request, $invitationCode)
    {
        $user = auth()->user();

        $session = $this->show($invitationCode);

        $player = $user->players()->create([
            'session_id' => $session->id,
        ]);

        return response()->json([
            'message' => 'Player succesfully added to the session!',
            'player' => $player
        ]);
    }
}