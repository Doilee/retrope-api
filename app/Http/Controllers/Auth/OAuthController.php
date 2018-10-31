<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class LoginController
 * @package App\Http\Controllers
 */
class OAuthController extends Controller
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
        /* @var \Laravel\Socialite\One\User $user */
        $socialite = Socialite::driver($driver)->stateless()->user();

        $user = User::createOrUpdate([
            'name' => $socialite->getNickname(),
        ],[
            'email' => $socialite->getEmail(),
            'driver' => $driver
        ]);

        Auth::login($user);
    }
}