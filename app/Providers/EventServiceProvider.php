<?php

namespace App\Providers;

use App\Action;
use App\Client;
use App\Http\Controllers\Employee\ActionController;
use App\Observers\ClientObserver;
use App\Observers\PlayerObserver;
use App\Observers\RetrospectiveObserver;
use App\Observers\UserObserver;
use App\Player;
use App\Retrospective;
use App\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        User::observe(UserObserver::class);
        Client::observe(ClientObserver::class);
        Player::observe(PlayerObserver::class);
        Retrospective::observe(RetrospectiveObserver::class);
        Action::observe(ActionController::class);


        //
    }
}
