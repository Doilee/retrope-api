<?php

namespace Tests\Feature;

use App\User;
use DateTime;
use DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', config('app.url')
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
    }

    /**
     * Test the login.
     *
     * @return void
     */
    public function testLogin()
    {
        $response = $this->post('/auth/login', [
            'email' => 'non-existing-email@retrope.com',
            'password' => 'bunnyboy'
        ]);

        $response->assertStatus(401);

        $user = factory(User::class)->create([
            'name' => 'test',
            'email' => 'test@retrope.com',
            'password' => bcrypt('bunnyboy123'),
        ]);

        $response = $this->post('/auth/login', [
            'email' => 'test@retrope.com',
            'password' => 'bunnyboy123'
        ]);

        $response->assertSuccessful();
    }

    public function testThrottler()
    {
        for ($i = 1;$i <= 5;$i++)
        {
            $response = $this->post('/auth/login', [
                'email' => 'spam@retrope.com',
                'password' => 'spammyboy'
            ]);

            if ($response->status() === 429) {
                dd('Login throttler fails at ' . $i . ' login attempts');
            }

            $response->assertStatus(401);
        }

        $response = $this->post('/auth/login', [
            'email' => 'spam@retrope.com',
            'password' => 'spammyboy'
        ])->assertStatus(429);
    }
}
