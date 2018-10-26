<?php

namespace Tests\Unit;

use Tests\PassportTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends PassportTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTooManyRequests()
    {
        for ($i = 1; $i <= 60; $i++){
            $response = $this->get('/me');
            $response->assertSuccessful();
        }
        $response = $this->get('/me');
        $response->assertStatus(429);
    }
}
