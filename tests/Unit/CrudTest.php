<?php

namespace Tests\Unit;

use App\Client;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Manager\UserController;
use App\Subscription;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        foreach ($this->scope() as $class => $case)
        {
            foreach($case['methods'] as $resource) {
                $this->{$resource . 'Test'}($class, $case);
            }
        }
    }

    protected function storeTest($class, $case)
    {
        $urlName = strtolower(array_last(explode('\\', $class)));

        $request = Request::create('/' . $urlName, 'POST', $case['store_request']);

        $this->assertCount(0, $this->app->make($class)->all());

        $this->app->make($case['controller'])->store($request);

        $this->assertCount(1, $this->app->make($class)->all());
    }

    protected function updateTest($class, $case)
    {
        $urlName = strtolower(array_last(explode('\\', $class)));

        $model = $this->app->make($class)->first() ?? factory($class)->create();

        $request = Request::create('/' . $urlName . '/' . $model->id, 'PUT', $case['update_request']);

        $this->assertCount(1, $this->app->make($class)->all());

        $this->app->make($case['controller'])->update($request, $model);

        $this->assertCount(1, $this->app->make($class)->all());

        foreach($case['update_request'] as $key => $value) {
            $this->assertEquals($model->$key, $value);
        }
    }

    protected function destroyTest($class, $case)
    {
        $model = $this->app->make($class)->first() ?? factory($class)->create();

        $this->assertCount(1, $this->app->make($class)->all());

        $this->app->make($case['controller'])->destroy($model);

        $this->assertCount(0, $this->app->make($class)->all());
    }

    protected function scope()
    {
        return [
            Client::class => [
                'controller' => ClientController::class,
                'store_request' => [
                    'name' => 'RETROPE'
                ],
                'update_request' => [
                    'name' => 'RETROPICAL'
                ],
                'methods' => ['store', 'update', 'destroy']
            ]
        ];
    }
}
