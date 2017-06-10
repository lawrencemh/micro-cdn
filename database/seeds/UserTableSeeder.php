<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
                ['email' => 'admin@cdn.com', 'id' => 1],
                [
                    'name' => 'Admin',
                    'password' => app('hash')->make('password'),
                    'is_admin' => true,
                ]
            );
    }
}
