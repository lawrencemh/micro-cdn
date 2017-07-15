<?php

namespace App\Services\Container;

use App\Models\Container;
use App\Repositories\Contracts\ContainerRepositoryInterface;

class DeleteService
{
    /**
     * The container instance.
     *
     * @var \App\Models\Container
     */
    protected $container;

    /**
     * The container repository instance.
     *
     * @var \App\Repositories\Contracts\ContainerRepositoryInterface
     */
    protected $containerRepository;

    /**
     * DeleteService constructor.
     *
     * @param \App\Models\Container $container
     * @param \App\Repositories\Contracts\ContainerRepositoryInterface $containerRepository
     * @return void
     */
    public function __construct(Container $container, ContainerRepositoryInterface $containerRepository)
    {
        $this->container = $container;
        $this->containerRepository = $containerRepository;
    }

    /**
     * Delete the given container from storage.
     *
     * @return \App\Models\Container
     */
    public function delete()
    {
        $this->containerRepository->delete($this->container);

        // @todo delete all of the container's media items in storage

        return $this->container;
    }
}