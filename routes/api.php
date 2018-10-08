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

    Route::get('login/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('login/{driver}/callback', 'Auth\OAuthController@handleProviderCallback');

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
    Route::post('retrospective/create', 'RetrospectiveController@create');
    Route::get('retrospective/{retrospective}', 'RetrospectiveController@show');
    Route::get('retrospective/{retrospective}/timer', 'RetrospectiveController@timeLeft');

    Route::put('retrospective/{retrospective}/join', 'RetrospectiveController@join');
    Route::put('retrospective/{retrospective}/start', 'RetrospectiveController@start');

    Route::post('retrospective/{retrospective}/invite', 'InvitationController@invite');
    Route::post('invite/{token}/accept', 'InvitationController@accept');
    // Route::put('retrospective/{retrospective}/invitation/create', 'InvitationController@create');

    // actions
    Route::post('retrospective/{retrospective}/action/create', 'ActionController@create');
    Route::put('action/{action}', 'ActionController@update');

    Route::post('action/{action}/vote', 'ActionController@vote');
    Route::delete('vote/{vote}', 'ActionController@vote');
    Route::put('action/{action}/like', 'ActionController@like');
    Route::put('action/{action}/dislike', 'ActionController@dislike');

});