<?php

use App\Client;
use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'employee']);
        Role::create(['name' => 'guest']);

        // Matthijs
        $admin = factory(User::class)->create([
            'name' => 'Administrator',
            'email' => 'admin@retrope.com',
        ]);
        $admin->assignRole('admin');
        $admin->save();

        $client = factory(Client::class)->create();

        $manager = factory(User::class)->create([
            'name' => 'Manager',
            'email' => 'manager@retrope.com',
            'client_id' => $client->id
        ]);
        $manager->assignRole('manager');
        $manager->save();

        $employee = factory(User::class)->create([
            'name' => 'Employee',
            'email' => 'employee@retrope.com',
            'client_id' => $client->id
        ]);
        $employee->assignRole('employee');
        $employee->save();
    }
}
