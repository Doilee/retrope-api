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
    public function testCrud()
    {
        foreach ($this->scope() as $model => $case)
        {
            $urlName = strtolower(array_last(explode('\\', $model)));

            foreach($case['resources'] as $resource) {
                $this->{$resource . 'Test'}($urlName, $model, $case);
            }
        }
    }

    private function storeTest($urlName, $model, $case)
    {
        $request = Request::create('/' . $urlName, 'POST', $case['request']);

        $this->assertCount(0, app($model)->all());

        app($case['controller'])->store($request);

        $this->assertCount(1, app($model)->all());
    }

    private function destroyTest($urlName, $model, $case)
    {
        $model = app($model)->first() ?? factory($model)->create();

        $this->assertCount(1, $model->all());

        app($case['controller'])->destroy($model);

        $this->assertCount(0, $model->all());
    }

    private function scope()
    {
        return [
            Client::class => [
                'controller' => ClientController::class,
                'request' => [
                    'type' => array_random(Subscription::TYPES)
                ],
                'resources' => ['store', 'destroy']
            ]
        ];
    }
}
