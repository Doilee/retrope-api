<?php

namespace App\Mail;

use App\Invite;
use App\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlayerInvited extends Mailable
{
    use Queueable, SerializesModels;

    protected $invite = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(?Invite $invite)
    {
        $this->invite = $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.participant_invited', [
            'invite' => $this->invite
        ]);
    }
}
