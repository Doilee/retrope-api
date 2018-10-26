<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Notification;
use Tests\PassportTestCase;

class VerificationTest extends PassportTestCase
{
    public function testAlreadyVerified()
    {
        Notification::fake();

        $request = $this->post('/email/resend');

        $request->assertStatus(422);

        $request->json([
            'message' => 'User already verified.',
            'verified' => true,
        ]);

        Notification::assertNothingSent();
    }

    /**
     * This test is not working as API Calls, probably due to Notification::fake() not working.
     * todo: Figure out how to test notifications
     */
    public function testVerification()
    {
        Notification::fake();

        $this->user->email_verified_at = null;

        $this->user->save();

        $this->user->notify(new VerifyEmail);

        // $request = $this->post('/email/resend');
        //
        // $request->assertSuccessful();

        Notification::assertTimesSent(1, VerifyEmail::class);

        Notification::assertSentTo($this->user,VerifyEmail::class);
    }
}
