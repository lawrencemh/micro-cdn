<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface ContainerRepositoryInterface extends BaseRepositoryInterface
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

}
