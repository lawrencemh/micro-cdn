<?php

namespace App\Services\Container;

trait SaveTrait
{
    /**
     * Commit and save the container instance to storage.
     *
     * @return \App\Models\Container
     */
    public function save()
    {
        $this->container->save();

        return $this->container;
    }
}