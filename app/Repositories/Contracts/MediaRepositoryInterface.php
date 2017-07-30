<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\Models\Container;

interface MediaRepositoryInterface
{
    /**
     * Get all media items belonging to the given container.
     *
     * @param \App\Models\Container $container
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMediaBelongingToContainer(Container $container);
}