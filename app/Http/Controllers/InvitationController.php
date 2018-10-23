<?php

namespace App\Http\Controllers;

use App\Exceptions\RetrospectiveException;
use App\Invite;
use App\Mail\PlayerInvited;
use App\Player;
use App\Retrospective;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    /**
     * @param Request $request
     * @param Retrospective $retrospective
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invite(Request $request, Retrospective $retrospective, User $user)
    {
        $manager = Auth::user();

        $users = $manager->client->users;

        // Throw exception when user isn't part of the client
        $user = $users->where('id', $user->id)->firstOrFail();

        // Create player field if doesnt exist
        $player = $retrospective->players()->where('user_id', $user->id)->first() ?? $retrospective->players()->create([
            'user_id' => $user->id,
        ]);

        /* @var Invite $invite */
        $invite = $player->invites()->create([
            'token' => str_random(),
        ]);

        Mail::to($user)->send(new PlayerInvited($invite));
        // mail to email

        return response()->json([
            'message' => 'User invited!'
        ]);
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function accept(Retrospective $retrospective)
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

        $player->joined_at = now();

        $player->save();

        return response()->json([
            'message' => 'Player joined the game!',
            'player' => $player,
        ]);
    }
}
