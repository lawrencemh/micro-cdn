<?php

namespace App\Services;

use App\Models\User;
use App\Models\Container;
use App\Services\Container\DeleteService;
use App\Repositories\Contracts\ContainerRepositoryInterface;

class ContainerService extends AbstractBaseService
{
    /**
     * ContainerService constructor.
     *
     * @param \App\Repositories\Contracts\ContainerRepositoryInterface $containerRepository
     * @return void
     */
    function __construct(ContainerRepositoryInterface $containerRepository)
    {
        $this->repository = $containerRepository ?? app(ContainerRepositoryInterface::class);
    }

    /**
     * Return all containers that belong to the given user.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function getAllContainersBelongingToUser(User $user)
    {
        return $this->repository->getAllContainersBelongingToUser($user);
    }

    /**
     * Return the container belonging to a given user.
     *
     * @param \App\Models\User $user
     * @param                  $containerId
     * @return mixed
     */
    public function getContainerBelongingToUser(User $user, $containerId)
    {
        return $this->repository->getContainerBelongingToUser($user, $containerId);
    }

    /**
     * Delete the given container from storage.
     *
     * @param \App\Models\Container $containerß
     * @return \App\Models\Container
     */
    public function deleteContainer(Container $container)
    {
        return (new DeleteService(
            $container, app(ContainerService::class), app(MediaService::class)
        ))->delete();
    }
}
