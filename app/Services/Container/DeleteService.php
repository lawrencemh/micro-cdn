<?php

namespace App\Services\Container;

use App\Models\Container;

class DeleteService
{
    /**
     * The container instance.
     *
     * @var \App\Models\Container
     */
    protected $container;

    /**
     * DeleteService constructor.
     *
     * @param \App\Models\Container $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Delete the given container from storage.
     *
     * @return \App\Models\Container
     */
    public function delete()
    {
        $this->container->delete();

        // @todo delete all of the container's media items in storage

        return $this->container;
    }
}