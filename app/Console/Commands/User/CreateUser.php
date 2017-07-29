<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * The faker instance.
     *
     * @var \Faker\Factory
     */
    protected $faker;

    /**
     * CreateUser constructor.
     *
     * @param \App\Models\User $user
     * @param \Faker\Factory $faker
     */
    public function __construct(User $user, Faker $faker)
    {
        $this->user     = $user;
        $this->faker    = $faker::create();
        Parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @todo move into user()->create() service.
     */
    public function handle()
    {
        $name = $this->ask('What is the user\'s full name?');
        $email = $this->ask('What is the user\'s email?');
        $isAdmin = $this->confirm('Should this user be an admin?', false);

        $this->info("You are about to create the following user:");
        $this->table(['Name', 'Email', 'Is an admin'], [[$name, $email, $isAdmin]]);
        if ($this->confirm('Do you wish to continue?', false) === false) {
            return 3;
        }

        $this->user->name = $name;
        $this->user->email = $email;
        $this->user->is_admin = $isAdmin;
        $this->user->api_token = $this->faker->uuid;
        $this->user->save();

        $this->info("User #{$this->user->id} has been successfully created. Their api token is:");
        $this->info($this->user->api_token);
        return 0;
    }
}
