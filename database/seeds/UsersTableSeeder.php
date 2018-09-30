<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Matthijs
        factory(User::class)->create([
            'name' => 'Doilee',
            'email' => 'matthijs0894@hotmail.com',
            'password' => bcrypt('jackass')
        ]);
    }
}
