<?php

namespace App\Http\Controllers;

use App\User;
use App\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class AuthController extends Controller
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }
    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.

        return JWTAuth::fromUser($user);
    }

    // public function register(Request $request)
    // {
    //     $this->validate($request, [
    //         'name' => 'required|string|min:2',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|string|min:6|confirmed'
    //     ]);
    //
    //     $user = new User($request->all());
    //     $user->password = app('hash')->make($request->get('password'));
    //
    //     $user->save();
    //
    //     return response()->json([
    //         'message' => 'User succesfully registered',
    //     ]);
    // }

    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = new User($request->all());
        $user->password = app('hash')->make($request->get('password'));

        $user->save();
        $verificationCode = str_random(30); //Generate verification code

        $user->verification()->create([
            'token' => $verificationCode
        ]);

        Mail::send('email.verify', [
            'name' => $user->username,
            'verificationCode' => $verificationCode
        ], function($msg) use ($user) {
            $msg->to($user->email);
            $msg->from('admin@retrospectre.com');
        });

        return response()->json([
            'message' => 'Thanks for signing up! Please check your email to complete your registration.'
        ]);
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function login(Request $request, User $user)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // Find the user by email
        $user = User::where('username', '=', $request->get('username'))->first();

        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the
            // below respose for now.
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($request->get('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }

        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    /**
     * API Verify User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($code)
    {
        /* @var \App\UserVerification $check */
        $check = UserVerification::where('token', $code)->first();

        if($check) {
            if($check->user->verified_at) {
                return response()->json([
                    'message'=> 'Account already verified..'
                ]);
            }

            $check->user->verify();

            $check->delete();

            return response()->json([
                'message'=> 'You have successfully verified your email address.'
            ]);
        }

        return response()->json(['success' => false, 'error' => "Verification code is invalid."]);
    }
}