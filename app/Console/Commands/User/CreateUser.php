<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-user';

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * CreateUser constructor.
     *
     * @param \App\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        if (strlen($name) < 1) {
            $this->error("[$name] is not a valid name!");
            return 1;
        }

        $email = $this->ask('What is the user\'s email?');
        if (strlen($email) < 1) {
            $this->error("[$email] is not a valid name!");
            return 2;
        }

        $isAdmin = $this->confirm('Should this user be an admin?', false);

        $this->info("You are about to create the following user:");
        $this->table(['Name', 'Email', 'Is an admin'], [[$name, $email, $isAdmin]]);
        if ($this->confirm('Do you wish to continue?', false) === false) {
            return 3;
        }

        $this->user->name = $name;
        $this->user->email = $email;
        $this->user->is_admin = $isAdmin;
        $this->user->api_token = uniqid();
        $this->user->save();

        $this->info("User #{$this->user->id} has been successfully created. Their api token is:");
        $this->info($this->user->api_token);
        return 0;
    }
}
