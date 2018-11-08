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

Route::get('/', 'Auth\LoginController@welcome');

// AUTH
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/create', 'Auth\ResetPasswordController@create');
    Route::get('password/find/{token}', 'Auth\ResetPasswordController@find');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::get('login/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('login/{driver}/callback', 'Auth\OAuthController@handleProviderCallback');
});

Route::group([
  'middleware' => 'auth:api'
], function() {
    Route::get('auth/logout', 'Auth\LoginController@logout');

    Route::get('me', 'ProfileController@me');
    Route::post('profile/edit', 'ProfileController@edit');

    Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::group(['middleware' => 'role:admin'], function() {
        Route::resource('client', 'Admin\ClientController', ['only' => [
            'index', 'store', 'show', 'update', 'destroy'
        ]]);

        Route::post('client/{client}/subscription', 'Admin\SubscriptionController@create');

        Route::resource('subscription', 'Admin\SubscriptionController', ['only' => [
            'update', 'destroy'
        ]]);
    });

    Route::group(['middleware' => 'role:admin|manager'], function() {
        Route::resource('user', 'Manager\UserController', ['only' => [
            'index', 'store', 'show', 'update', 'destroy'
        ]]);

        Route::resource('team', 'Manager\TeamController', ['only' => [
            'index', 'store', 'show', 'update', 'destroy'
        ]]);

        Route::put('team/{team}/user/{user}', 'Manager\TeamController@attachUser');
        Route::delete('team/{team}/user/{user}', 'Manager\TeamController@detachUser');

        Route::post('user/{user}/sendverification', 'Manager\UserController@sendVerificationToUser');

        Route::post('retrospective/create', 'Manager\RetrospectiveController@create');
        Route::get('retrospective/{retrospective}', 'Manager\RetrospectiveController@show');
        Route::put('retrospective/{retrospective}/start', 'Manager\RetrospectiveController@start');

        Route::post('retrospective/{retrospective}/invite/{user}', 'Manager\RetrospectiveController@invite');
    });

    Route::group(['middleware' => 'role:employee'], function() {

        Route::get('retrospective/{retrospective}/timer', 'Employee\RetrospectiveController@timeLeft');

        Route::put('retrospective/{retrospective}/join', 'Employee\RetrospectiveController@join');

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