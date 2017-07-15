<?php

namespace App\Services\Container;

use App\Models\User;
use App\Models\Container;

class CreateService
{
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
     * Set the name of the container.
     *
     * @param stirng $name
     * @return $this
     */
    public function setName($name)
    {
        $this->container->name = $name;

        return $this;
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

    /**
     * Commit and save the container instance to storage.
     *
     * @return \App\Models\Container
     */
    public function save()
    {
        $this->container->save();

        return $this->container;
    }
}