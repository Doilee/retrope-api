<?php

namespace App\Http\Controllers;

use App\Session;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function create(Request $request, Session $session)
    {
        $this->validate($request, [
            'scheduled_at' => 'nullable|date|after:' . now()->toDateTimeString()
        ]);

        $invitation = $session->invitations()->updateOrCreate([
                'scheduled_at' => $request->get('scheduled_at'),
            ], [
                'code' => str_random()
            ]);

        return response()->json([
            'message' => 'Invitation created.',
            'invitation' => $invitation
        ], 201);
    }
}
