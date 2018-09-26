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

// AUTH
Route::group([
    'prefix' => 'auth'
], function () {
    Route::get('email/verify', 'Auth\VerificationController@show');
    Route::get('email/resend', 'Auth\VerificationController@resend');

    Route::post('login', 'AuthController@login');
    Route::post('recover', 'AuthController@recover');
    Route::post('register', 'AuthController@register');
    Route::post('password/reset', 'AuthController@resetPassword');
    // Route::get('user/verify/{verification_code}', 'AuthController@verifyUser');

    Route::post('login/guest', 'AuthController@guestSignIn');

    Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider');
    Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
    });

});

Route::group([
  'middleware' => 'auth:api'
], function() {
    Route::get('me', 'UserController@me');

    // SESSIONS
    Route::post('session/create', 'SessionController@create');
    Route::get('session/{session}', 'SessionController@show');

    Route::put('invitation/{code}/participate', 'SessionController@participate');

    Route::put('session/{session}/start', 'SessionController@start');
    Route::post('session/{session}/invite', 'SessionController@invite');
    Route::put('session/{session}/invitation/create', 'InvitationController@create');

    Route::post('session/{session}/retrospective/create', 'RetrospectiveController@create');
    Route::put('session/{session}/retrospective/{retrospective}', 'RetrospectiveController@update');

    // RETROSPECTIVES
    Route::put('retrospective/{retrospective}/like', 'RetrospectiveController@like');
    Route::put('retrospective/{retrospective}/dislike', 'RetrospectiveController@dislike');

});

// routes accessible when logged in
// Route::group([
//     'middleware' => 'jwt.auth'
// ], function() use ($router) {
//     $router->post('logout', 'AuthController@logout');
//
//     $router->get('session/create', 'SessionController@create');
//
//     //
// });