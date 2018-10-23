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
    Route::get('me', 'ProfileController@me');
    Route::post('profile/edit', 'ProfileController@edit');

    Route::put('email/verify/{user}', 'Auth\VerificationController@verify')->middleware('signed')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    // SESSIONS
    Route::post('retrospective/create', 'RetrospectiveController@create');
    Route::get('retrospective/{retrospective}', 'RetrospectiveController@show');
    Route::get('retrospective/{retrospective}/timer', 'RetrospectiveController@timeLeft');

    Route::put('retrospective/{retrospective}/join', 'RetrospectiveController@join');
    Route::put('retrospective/{retrospective}/start', 'RetrospectiveController@start');

    // actions
    Route::post('retrospective/{retrospective}/action/create', 'ActionController@create');
    Route::get('action/{action}', 'ActionController@show');
    Route::put('action/{action}', 'ActionController@update');

    Route::post('action/{action}/vote', 'ActionController@vote');
    Route::delete('vote/{vote}', 'ActionController@removeVote');

    Route::group(['middleware' => 'role:admin'], function() {
        Route::resource('client', 'Admin\ClientController', ['only' => [
            'index', 'store', 'show', 'update', 'destroy'
        ]]);
    });
    Route::group(['middleware' => 'role:manager'], function() {
        Route::resource('user', 'Manager\UserController', ['only' => [
            'index', 'store', 'show', 'update', 'destroy'
        ]]);

        Route::post('retrospective/{retrospective}/invite/{user}', 'InvitationController@invite');
    });

    Route::group(['middleware' => 'role:employee'], function() {
        Route::post('retrospective/{retrospective}/join', 'InvitationController@accept');
    });
//    DEPRECATED:
//    Route::put('action/{action}/like', 'ActionController@like');
//    Route::put('action/{action}/dislike', 'ActionController@dislike');

});