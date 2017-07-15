<?php

namespace App\Services;

use App\Services\Container\CreateService;

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
}