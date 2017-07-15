<?php

namespace App\Services\Container;

use App\Models\Container;
use App\Repositories\Contracts\ContainerRepositoryInterface;

class UpdateService
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
     * The container repository instance.
     *
     * @var \App\Repositories\Contracts\ContainerRepositoryInterface
     */
    protected $containerRepository;

    /**
     * UpdateService constructor.
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

}