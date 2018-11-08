<?php

namespace Tests\Unit;

use App\Client;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Manager\TeamController;
use App\Http\Controllers\Manager\UserController;
use App\Subscription;
use App\Team;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCrud()
    {
        foreach ($this->scope() as $role => $case)
        {
            $user = factory(User::class)->create();
            $user->assignRole($role);
            $this->be($user);
            foreach ($case as $class => $data)
            {
                foreach($data['methods'] as $resource) {
                    $this->{$resource . 'Test'}($class, $data);
                }
            }
        }
    }

    protected function storeTest($class, $case)
    {
        $urlName = strtolower(array_last(explode('\\', $class)));

        $request = Request::create('/' . $urlName, 'POST', $case['store_request']);

        $count = $this->app->make($class)->all()->count();

        $this->app->make($case['controller'])->store($request);

        $this->assertGreaterThan($count, $this->app->make($class)->all()->count(), $class);
    }

    protected function updateTest($class, $case)
    {
        $urlName = strtolower(array_last(explode('\\', $class)));

        $model = factory($class)->create();

        $request = Request::create('/' . $urlName . '/' . $model->id, 'PUT', $case['update_request']);

        $this->app->make($case['controller'])->update($request, $model);

        foreach($case['update_request'] as $key => $value) {
            $this->assertEquals($model->$key, $value);
        }

        $this->assertEquals($model->all()->count(), $this->app->make($class)->all()->count(), $class);
    }

    protected function destroyTest($class, $case)
    {
        $model = $this->app->make($class)->first() ?? factory($class)->create();

        $count = $model->all()->count();

        $this->assertGreaterThanOrEqual(1, $count);

        $this->app->make($case['controller'])->destroy($model);

        $this->assertLessThan($count, $this->app->make($class)->all()->count(), $class);
    }

    protected function scope()
    {
        return [
            'admin' => [
                Client::class => [
                    'controller' => ClientController::class,
                    'store_request' => [
                        'name' => 'RETROPE'
                    ],
                    'update_request' => [
                        'name' => 'RETROPICAL'
                    ],
                    'methods' => ['store', 'update', 'destroy']
                ],
                Subscription::class => [
                    'controller' => SubscriptionController::class,
                    'update_request' => [
                        'type' => 'pro',
                        'expires_at' => now()->addYears(2)->toDateTimeString(),
                    ],
                    'methods' => ['update', 'destroy']
                ],
                Team::class => [
                    'controller' => TeamController::class,
                    'update_request' => [
                        'name' => 'Meh Team'
                    ],
                    'methods' => ['update', 'destroy']
                ]
            ],
            'manager' => [
                Team::class => [
                    'controller' => TeamController::class,
                    'store_request' => [
                        'name' => 'Super Team'
                    ],
                    'methods' => ['store']
                ]
            ]
        ];
    }
}
