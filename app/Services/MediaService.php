<?php

namespace App\Services;

use App\Models\Container;
use App\Models\Media;
use App\Repositories\Contracts\MediaRepositoryInterface;
use App\Services\Media\CreateService;
use App\Services\Media\DeleteService;
use App\Services\Media\UpdateService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaService extends AbstractBaseService
{
    /**
     * The repository instance.
     *
     * @var \App\Repositories\Contracts\BaseRepositoryInterface
     */
    protected $repository;

    /**
     * MediaService constructor.
     *
     * @param \App\Repositories\Contracts\MediaRepositoryInterface|null $mediaRepository
     *
     * @return void
     */
    public function __construct(MediaRepositoryInterface $mediaRepository = null)
    {
        $this->repository = $mediaRepository ?? app(MediaRepositoryInterface::class);
    }

    /**
     * Create a new media item for a given container and file.
     *
     * @param \App\Models\Container                               $container
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \App\Services\Media\CreateService
     */
    public function createMediaItem(Container $container, UploadedFile $file)
    {
        return new CreateService(app(self::class), $container, $file);
    }

    /**
     * Update a given media item.
     *
     * @param \App\Models\Media $media
     *
     * @return \app\Services\Media\UpdateService
     */
    public function update(Media $media)
    {
        return new UpdateService($media);
    }

    /**
     * Delete the media item from storage and queue a job to delete the physical file
     * in storage.
     *
     * @param \App\Models\Media $media
     *
     * @return \App\Models\Media
     */
    public function delete(Media $media)
    {
        return (new DeleteService(app(CompressedCopyService::class), $media, app(self::class)))
            ->delete();
    }

    /**
     * Get all media items belonging to the given container.
     *
     * @param \App\Models\Container $container
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMediaBelongingToContainer(Container $container)
    {
        return $this->repository->getAllMediaBelongingToContainer($container);
    }

    /**
     * Get a media item belonging to a given collection.
     *
     * @param \App\Models\Container $container
     * @param int                   $mediaId
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getMediaItemBelongingToContainer(Container $container, $mediaId)
    {
        return $this->repository->getMediaItemBelongingToContainer($container, $mediaId);
    }
}
