<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback($driver)
    {
        $user = Socialite::driver($driver)->stateless()->user();

        $user = User::createOrUpdate([
            'nickname' => $user->getNickname(),
        ],[
            'email' => $user->getEmail(),
            'driver' => $driver
        ]);

        Auth::login($user);
    }
}