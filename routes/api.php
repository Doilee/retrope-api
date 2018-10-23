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

Route::get('/', function (){
    return App::version();
});

// AUTH
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('password/reset', 'Auth\ForgotPasswordController@sendResetLinkEmail');

    Route::get('login/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('login/{driver}/callback', 'Auth\OAuthController@handleProviderCallback');
});

Route::group([
  'middleware' => 'auth:api'
], function() {
    Route::get('logout', 'AuthController@logout');

    Route::get('me', 'ProfileController@me');
    Route::post('profile/edit', 'ProfileController@edit');

    Route::put('email/verify/{user}', 'Auth\VerificationController@verify')->middleware('signed')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');


    Route::group(['middleware' => 'role:admin'], function() {
        Route::resource('client', 'Admin\ClientController', ['only' => [
            'index', 'store', 'show', 'update', 'destroy'
        ]]);
    });
    Route::group(['middleware' => 'role:manager'], function() {
        Route::resource('user', 'Manager\UserController', ['only' => [
            'index', 'store', 'show', 'update', 'destroy'
        ]]);

        Route::post('retrospective/create', 'Manager\RetrospectiveController@create');
        Route::get('retrospective/{retrospective}', 'Manager\RetrospectiveController@show');
        Route::put('retrospective/{retrospective}/start', 'Manager\RetrospectiveController@start');

        Route::post('retrospective/{retrospective}/invite/{user}', 'Manager\InvitationController@invite');

        Route::post('user/{user}/sendverification', 'Manager\UserController@sendVerificationToUser');
    });

    Route::group(['middleware' => 'role:employee'], function() {

        Route::get('retrospective/{retrospective}/timer', 'Employee\RetrospectiveController@timeLeft');

        // Test join todo: Comment when going live
        Route::put('retrospective/{retrospective}/join', 'Employee\RetrospectiveController@join');
        // Real join todo: Uncomment when going live
        // Route::put('retrospective/{retrospective}/join', 'InvitationController@acceptInvite');

        // actions
        Route::post('retrospective/{retrospective}/action/create', 'Employee\ActionController@create');
        Route::get('action/{action}', 'Employee\ActionController@show');
        Route::put('action/{action}', 'Employee\ActionController@update');

        Route::post('action/{action}/vote', 'Employee\ActionController@vote');
        Route::delete('vote/{vote}', 'Employee\ActionController@removeVote');
    });

//    DEPRECATED:
//    Route::put('action/{action}/like', 'ActionController@like');
//    Route::put('action/{action}/dislike', 'ActionController@dislike');

});