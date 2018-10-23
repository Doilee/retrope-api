<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * Create token password reset
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse [string] message
     * @internal param $ [string] email
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user)  {
            return response()->json([
                'message' => 'We can\'t find a user with that e-mail address.'
            ], 404);
        }

        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email
        ], [
                'email' => $user->email,
                'token' => str_random(60)
            ]
        );

        $user->notify(new PasswordResetRequest($passwordReset->token));

        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);
    }

    /**
     * Find token password reset
     *
     * @param $token
     *
     * @return \Illuminate\Http\JsonResponse [string] message
     * @internal param $ [string] $token
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset)  {
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }

        if (Carbon::parse($passwordReset->created_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }

        return response()->json([
            'message' => 'User password has been reset.',
            'reset' => $passwordReset,
        ]);
    }

    /**
     * Reset password
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse [string] message
     * @internal param $ [string] email
     * @internal param $ [string] password
     * @internal param $ [string] password_confirmation
     * @internal param $ [string] token
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'We can\'t find a user with that e-mail address.'
            ], 404);
        }

        $user->password = bcrypt($request->password);

        $user->save();

        $passwordReset->delete();

        $user->notify(new PasswordResetSuccess());

        return response()->json([
            'message' => 'User password has been reset.',
            'user' => $user,
        ]);
    }
}
