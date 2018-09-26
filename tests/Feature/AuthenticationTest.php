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
     * Test the simple base URL
     */
    public function testVersion()
    {
        $response = $this->get('/');

        $response->assertSuccessful();
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

        $response = $this->post('/auth/register', [
            'name' => 'LaravelPassport',
            'email' => 'passported@retrope.com',
            'password' => 'bunnyboy123',
            'password_confirmation' => 'bunnyboy123',
        ]);

        $response->assertSuccessful();

        $response = $this->post('/auth/login', [
            'email' => 'passported@retrope.com',
            'password' => 'bunnyboy123'
        ]);

        $response->assertSuccessful();
    }
}
