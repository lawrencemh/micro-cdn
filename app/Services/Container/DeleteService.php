<?php

namespace App\Services\Container;

use App\Models\Container;
use App\Services\MediaService;
use App\Services\ContainerService;

class DeleteService
{
    /**
     * The container instance.
     *
     * @var \App\Models\Container
     */
    protected $container;

    /**
     * The container service instance.
     *
     * @var \App\Services\ContainerService
     */
    protected $containerService;

    /**
     * The media service instance.
     *
     * @var \App\Services\MediaService
     */
    protected $mediaService;

    /**
     * DeleteService constructor.
     *
     * @param \App\Models\Container          $container
     * @param \App\Services\ContainerService $containerService
     * @param \App\Services\MediaService     $mediaService
     * @return void
     */
    public function __construct(
        Container $container,
        ContainerService $containerService,
        MediaService $mediaService
    )
    {
        $this->container           = $container;
        $this->containerService    = $containerService;
        $this->mediaService        = $mediaService;
    }

    /**
     * Delete the given container from storage.
     *
     * @return \App\Models\Container
     */
    public function delete()
    {
        foreach ($this->container->media as $mediaItem) {
            $this->mediaService->delete($mediaItem);
        }

        $this->containerService->destroy($this->container->id);

        return $this->container;
    }
}
