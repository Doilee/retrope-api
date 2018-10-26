<?php

namespace Tests\Unit;

use App\Client;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Manager\UserController;
use App\Subscription;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        foreach ($this->storables() as $model => $storable)
        {
            $urlName = strtolower(array_last(explode('\\', $model)));

            $request = Request::create('/' . $urlName, 'POST', $storable['request']);

            $this->assertCount(0, app($model)->all());

            app($storable['controller'])->store($request);

            $this->assertCount(1, app($model)->all());
        }
    }

    private function storables()
    {
        return [
            Client::class => [
                'controller' => ClientController::class,
                'request' => [
                    'type' => array_random(Subscription::TYPES)
                ]
            ]
        ];
    }
}
