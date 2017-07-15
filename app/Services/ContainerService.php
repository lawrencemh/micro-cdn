<?php

namespace App\Services;

use App\Models\Container;
use App\Services\Container\CreateService;
use App\Services\Container\UpdateService;

class ContainerService
{
    /**
     * Create a new container.
     *
     * @return \App\Services\Container\CreateService
     */
    public function create()
    {
        return new CreateService;
    }

    /**
     * Update an existing container.
     *
     * @param \App\Models\Container $container
     * @return \App\Services\Container\UpdateService
     */
    public function update(Container $container)
    {
        return new UpdateService($container);
    }
}