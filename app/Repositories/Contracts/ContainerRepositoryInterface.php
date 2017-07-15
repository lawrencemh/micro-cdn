<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\Models\Container;

interface ContainerRepositoryInterface
{
    /**
     * Return all containers that belong to the given user.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function getAllContainersBelongingToUser(User $user);

    /**
     * Return the container belonging to a given user.
     *
     * @param \App\Models\User $user
     * @param $containerId
     * @return mixed
     */
    public function getContainerBelongingToUser(User $user, $containerId);

    /**
     * Delete the given container from storage.
     *
     * @param \App\Models\Container $container
     * @return bool
     */
    public function delete(Container $container);
}