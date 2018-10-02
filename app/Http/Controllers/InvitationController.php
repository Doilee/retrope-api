<?php

namespace App\Http\Controllers;

use App\Invite;
use App\Mail\PlayerInvited;
use App\Player;
use App\Session;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
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

        $user = $this->createGuestAccount($request->get('email'));

        /* @var Invite $invite */
        $invite = $session->invites()->updateOrCreate([
            'user_id' => $user->id,
            'token' => str_random(),
        ]);

        Mail::to($user)
            ->send(new PlayerInvited($invite));
        // mail to email

        return response()->json([
            'message' => 'User invited!'
        ]);
    }

    /**
     * @param $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept($token)
    {
        /* @var Invite $invite */
        if (!$invite = Invite::where('token', $token)->first()) {
            //if the invite doesn't exist do something more graceful than this
            abort(404);
        }

        $user = $invite->user;

        /* @var Player $player */
        $player = $invite->session->players()->create([
            'user_id' => $user->id
        ]);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->expires_at = Carbon::now()->addDays(7);

        $token->save();

        $invite->delete();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $token->expires_at
            )->toDateTimeString(),
            'player' => $player,
        ]);
    }

    /**
     * @param string $email
     */
    private function createGuestAccount(string $email)
    {
        User::create([
            'email' => $email,
            'password' => bcrypt(uniqid('', true)),
            'driver' => 'guest',
            'email_verified_at' => now()->toDateTimeString(),
        ]);
    }
}
