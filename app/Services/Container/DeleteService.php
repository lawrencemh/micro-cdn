<?php

namespace App\Services\Container;

use App\Models\Container;
use App\Services\MediaService;
use App\Repositories\Contracts\ContainerRepositoryInterface;

class DeleteService
{
    /**
     * The container instance.
     *
     * @var \App\Models\Container
     */
    protected $container;

    /**
     * The container repository instance.
     *
     * @var \App\Repositories\Contracts\ContainerRepositoryInterface
     */
    protected $containerRepository;

    /**
     * The media service instance.
     *
     * @var \App\Services\MediaService
     */
    protected $mediaService;

    /**
     * DeleteService constructor.
     * a*
     * @param \App\Models\Container                                    $container
     * @param \App\Repositories\Contracts\ContainerRepositoryInterface $containerRepository
     * @return void
     */
    public function __construct(
        Container $container,
        ContainerRepositoryInterface $containerRepository,
        MediaService $mediaService
    )
    {
        $this->container           = $container;
        $this->containerRepository = $containerRepository;
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

        $this->containerRepository->delete($this->container);

        return $this->container;
    }
}
