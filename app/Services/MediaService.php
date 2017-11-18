<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Container;
use App\Services\Media\CreateService;
use App\Services\Media\DeleteService;
use App\Services\Media\UpdateService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repositories\Contracts\MediaRepositoryInterface;

class MediaService
{
    /**
     * Create a new media item for a given container and file.
     *
     * @param \App\Models\Container                               $container
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return \App\Services\Media\CreateService
     */
    public function create(Container $container, UploadedFile $file)
    {
        return new CreateService(app(MediaRepositoryInterface::class), $container, $file);
    }

    /**
     * Update a given media item.
     *
     * @param \App\Models\Media $media
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
     * @return \App\Models\Media
     */
    public function delete(Media $media)
    {
        return (new DeleteService($media, app(MediaRepositoryInterface::class)))->delete();
    }
}
