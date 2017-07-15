<?php

namespace App\Services;

use App\Models\Container;
use App\Services\Container\CreateService;
use App\Services\Container\DeleteService;
use App\Services\Container\UpdateService;
use App\Repositories\Contracts\ContainerRepositoryInterface;

class ContainerService
{
    /**
     * Create a new container.
     *
     * @return \App\Services\Container\CreateService
     */
    public function create()
    {
        return new CreateService(app(ContainerRepositoryInterface::class));
    }

    /**
     * Update an existing container.
     *
     * @param \App\Models\Container $container
     * @return \App\Services\Container\UpdateService
     */
    public function update(Container $container)
    {
        return new UpdateService($container, app(ContainerRepositoryInterface::class));
    }

    /**
     * Delete the given container from storage.
     *
     * @param \App\Models\Container $container
     * @return \App\Models\Container
     */
    public function delete(Container $container)
    {
        return (new DeleteService(
            $container, app(ContainerRepositoryInterface::class)
        ))->delete();
    }
}