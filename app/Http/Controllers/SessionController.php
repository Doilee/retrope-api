<?php namespace App\Http\Controllers;

use App\Session;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2',
        ]);

        /** @var User $host */
        $host = Auth::user();

        $invitationCode = str_random();

        $host->session()->create([
            'name' => $request->get('name'),
            'invitation_code' => $invitationCode,
            'started_at' => now()
        ]);

        return response()->json([
            'message' => 'Success',
            'invitation_code' => $invitationCode
        ]);
    }
}