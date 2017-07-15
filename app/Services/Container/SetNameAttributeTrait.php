<?php

namespace App\Services\Container;

trait SetNameAttributeTrait
{
    /**
     * Set the name of the container.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->container->name = $name;

        return $this;
    }
}