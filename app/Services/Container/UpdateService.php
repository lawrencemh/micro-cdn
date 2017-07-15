<?php

namespace App\Services\Container;

use App\Models\Container;

class UpdateService
{
    use SetNameAttributeTrait;

    /**
     * The container instance.
     *
     * @var \App\Models\Container
     */
    protected $container;

    /**
     * UpdateService constructor.
     *
     * @param \App\Models\Container $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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