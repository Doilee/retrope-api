<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\invitation;
use App\Mail\ParticipantInvited;
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
        $invitations = Invitation::where('scheduled_at', '<', now()->addMinutes(5)->toDateTimeString())->get();

        $invitations->each(function(Invitation $invitation) {
            $invitation->session->players()->each(function(Player $player) {
                Mail::to($player->user)
                    ->send(new ParticipantInvited($player->session));
            });
            $invitation->send_at = now()->toDateTimeString();
            $invitation->save();
        });
    }
}
