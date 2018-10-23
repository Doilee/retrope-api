<?php

namespace Tests\Feature;

use App\User;
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

    public function testVerification()
    {
        Notification::fake();

        $this->user->email_verified_at = null;

        $this->user->save();

        $request = $this->post('/email/resend');

        $request->assertSuccessful();

        Notification::assertSentTo($this->user,VerifyEmail::class);
    }
}
