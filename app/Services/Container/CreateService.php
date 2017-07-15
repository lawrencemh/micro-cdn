<?php

namespace App\Services\Container;

use App\Models\User;
use App\Models\Container;
use App\Repositories\Contracts\ContainerRepositoryInterface;

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
     * The container repository instance.
     *
     * @var \App\Repositories\Contracts\ContainerRepositoryInterface
     */
    protected $containerRepository;

    /**
     * CreateService constructor.
     *
     * @param \App\Repositories\Contracts\ContainerRepositoryInterface $containerRepository
     * @return void
     */
    public function __construct(ContainerRepositoryInterface $containerRepository)
    {
        $this->container = new Container;
        $this->containerRepository = $containerRepository;
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