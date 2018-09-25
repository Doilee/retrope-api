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
    Route::post('recover', 'AuthController@recover');
    Route::post('register', 'AuthController@register');
    Route::post('password/reset', 'AuthController@resetPassword');
    Route::get('user/verify/{verification_code}', 'AuthController@verifyUser');

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
    Route::get('user', 'AuthController@user');

    // SESSIONS
    Route::post('session/create', 'SessionController@create');
    Route::get('session/{invitationCode}', 'SessionController@show');

    Route::put('session/{invitationCode}/participate', 'SessionController@participate');

    Route::put('session/{session}/start', 'SessionController@start');
    Route::put('session/{session}/invite', 'SessionController@invite');
    Route::put('session/{session}/schedule', 'SessionController@scheduleInvitation');

    Route::post('session/{invitationCode}/retrospective/create', 'RetrospectiveController@create');
    Route::put('session/{invitationCode}/retrospective/{retrospective}', 'RetrospectiveController@update');

    // RETROSPECTIVES
    Route::post('retrospective/{retrospective}/like', 'RetrospectiveController@like');
    Route::post('retrospective/{retrospective}/dislike', 'RetrospectiveController@dislike');

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