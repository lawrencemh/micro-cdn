<?php

use App\Models\User;
use Faker\Factory as Faker;
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
        $faker = Faker::create();

        User::firstOrCreate(
                ['email' => 'admin@cdn.com', 'id' => 1],
                [
                    'name'      => 'Admin',
                    'password'  => app('hash')->make('password'),
                    'api_token' => $faker->uuid,
                    'is_admin'  => true,
                ]
            );
    }
}
