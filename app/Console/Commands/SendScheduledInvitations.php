<?php

namespace App\Console\Commands;

use App\Notifications\PlayerInvited;
use App\Retrospective;
use Illuminate\Console\Command;

use App\Player;

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
        $retrospectives = Retrospective::where('scheduled_at', '<', now()->addMinutes(10)->toDateTimeString())
            ->where('starts_at', '=', null)
            ->get();

        $retrospectives->each(function(Retrospective $retrospective) {
            $retrospective->players()->each(function(Player $player) {
                $player->user->notify(new PlayerInvited);
            });
        });
    }
}
