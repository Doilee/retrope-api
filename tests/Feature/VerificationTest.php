<?php

namespace Tests\Feature;

use Illuminate\Auth\Notifications\VerifyEmail;
use Notification;
use Tests\PassportTestCase;

class VerificationTest extends PassportTestCase
{
    /**
     * Test the user verification
     */
    public function testVerify()
    {
        // $this->put('/auth/email/verify/' . $this->user->id)->dump();
    }

    public function testResend()
    {
        Notification::fake();

        $this->post('/email/resend')->assertSuccessful();

        Notification::assertSentTo($this->user,VerifyEmail::class);
    }
}
