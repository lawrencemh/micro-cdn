<?php

namespace App\Services\Container;

use App\Models\Container;

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
     * UpdateService constructor.
     *
     * @param \App\Models\Container $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

}