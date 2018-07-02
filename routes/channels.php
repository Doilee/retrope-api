<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Route::get('/', function () use ($router) {
    return $router->app->version();
});

Route::post('register', 'AuthController@register');

Route::post('login', 'AuthController@login');

Route::post('recover', 'AuthController@recover');

Route::get('user/verify/{verification_code}', 'AuthController@verifyUser');

Route::post('password/reset', 'AuthController@resetPassword');

// routes accessible when logged in
Route::group([
    'middleware' => 'jwt.auth'
], function() use ($router) {
    $router->post('logout', 'AuthController@logout');

    $router->get('users', function() {
        $users = \App\User::all();
        return response()->json($users);
    });

    //
});