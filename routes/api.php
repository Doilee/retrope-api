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

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('password/reset', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    // Route::post('recover', 'AuthController@recover');
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
    Route::post('profile/edit', 'UserController@edit');

    Route::put('email/verify/{user}', 'Auth\VerificationController@verify')->middleware('signed')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    // SESSIONS
    Route::post('session/create', 'SessionController@create');
    Route::get('session/{session}', 'SessionController@show');
    Route::get('session/{session}/timer', 'SessionController@timeLeft');

    Route::put('session/{session}/join', 'SessionController@join');
    Route::put('session/{session}/start', 'SessionController@start');

    Route::post('session/{session}/invite', 'InvitationController@invite');
    Route::post('invite/{token}/accept', 'InvitationController@accept');
    // Route::put('session/{session}/invitation/create', 'InvitationController@create');

    // RETROSPECTIVES
    Route::post('session/{session}/retrospective/create', 'RetrospectiveController@create');
    Route::put('retrospective/{retrospective}', 'RetrospectiveController@update');

    Route::post('retrospective/{retrospective}/vote', 'RetrospectiveController@vote');
    Route::put('retrospective/{retrospective}/like', 'RetrospectiveController@like');
    Route::put('retrospective/{retrospective}/dislike', 'RetrospectiveController@dislike');

});