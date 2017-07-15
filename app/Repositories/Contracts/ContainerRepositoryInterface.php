<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface ContainerRepositoryInterface
{
    /**
     * Return all containers that belong to the given user.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function getAllContainersBelongingToUser(User $user);
}