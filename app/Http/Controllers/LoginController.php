<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    /**
     * @param $driver
     *
     * @return mixed
     */
    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * @param $driver
     */
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