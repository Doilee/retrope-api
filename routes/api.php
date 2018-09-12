<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::get('/', function () use ($router) {
    return App::version();
});

Route::post('register', 'AuthController@register');

Route::post('login', 'AuthController@login');

Route::post('recover', 'AuthController@recover');

Route::get('user/verify/{verification_code}', 'AuthController@verifyUser');

Route::post('password/reset', 'AuthController@resetPassword');

Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');

// routes accessible when logged in
Route::group([
    'middleware' => 'jwt.auth'
], function() use ($router) {
    $router->post('logout', 'AuthController@logout');

    $router->get('session/create', 'SessionController@create');

    //
});