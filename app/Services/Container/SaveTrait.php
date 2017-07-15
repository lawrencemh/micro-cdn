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
        $this->containerRepository->save($this->container);

        return $this->container;
    }
}