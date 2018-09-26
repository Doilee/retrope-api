<?php

namespace App\Console\Commands;

use App\Session;
use Illuminate\Console\Command;

use App\Invite;
use App\Mail\PlayerInvited;
use App\Player;
use Mail;

class SendScheduledInvitations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:invite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the scheduled invitations for the sessions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sessions = Session::where('starts_at', '<', now()->addMinutes(10)->toDateTimeString())
            ->where('completed_at', '!=', null)
            ->get();

        $sessions->each(function(Session $session) {
            $session->players()->each(function(Player $player) {
                Mail::to($player->user)
                    ->send(new PlayerInvited($player->session));
            });
        });
    }
}
