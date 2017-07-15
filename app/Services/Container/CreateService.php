<?php

namespace App\Services\Container;

use App\Models\User;
use App\Models\Container;

class CreateService
{
    use SaveTrait;
    use SetNameAttributeTrait;

    /**
     * The container instance.
     *
     * @var \App\Models\Container
     */
    protected $container;

    /**
     * CreateService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->container = new Container;
    }

    /**
     * Set and associate the user that the container will belong to.
     *
     * @param \App\Models\User $user
     * @return $this
     */
    public function setOwner(User $user)
    {
        $this->container->user()->associate($user);

        return $this;
    }

}