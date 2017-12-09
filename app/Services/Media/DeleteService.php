<?php

namespace App\Services\Media;

use App\Models\Media;
use App\Services\MediaService;
use App\Exceptions\Services\Media\FailedToRemoveFromStorageException;

class DeleteService
{
    /**
     * The media model instance.
     *
     * @var \App\Models\Media
     */
    protected $media;

    /**
     * The media service instance.
     *
     * @var \App\Services\MediaService
     */
    protected $mediaService;

    /**
     * DeleteService constructor.
     *
     * @param \App\Models\Media          $media
     * @param \App\Services\MediaService $mediaService
     * @return void
     */
    function __construct(Media $media, MediaService $mediaService)
    {
        $this->media        = $media;
        $this->mediaService = $mediaService;
    }

    /**
     * Delete the media item from storage and queue a job to delete the physical file
     * in storage.
     *
     * @return \App\Models\Media
     * @throws \App\Exceptions\Services\Media\FailedToRemoveFromStorageException
     */
    public function delete()
    {
        try {
            // Start DB transaction to allow a rollback in the instance the file fails to remove from storage.
            app('db')->beginTransaction();
            $this->mediaService->destroy($this->media->id);

            // Check the file exists
            if (file_exists($this->getFullPath()) === false) {
                throw new FailedToRemoveFromStorageException("{$this->getFullPath()} does not exist in storage!");
            }

            // Physically remove file from storage
            if (unlink($this->getFullPath()) === false) {
                throw new FailedToRemoveFromStorageException('Failed to remove the file from storage');
            }

            // Successfully removed commit changes to database.
            app('db')->commit();

        } catch (FailedToRemoveFromStorageException $e) {

            app('db')->rollBack();
            throw $e;
        }

        return $this->media;
    }

    /**
     * Get the full system file path for the given media item.
     *
     * @return string
     */
    protected function getFullPath()
    {
        return public_path($this->media->path);
    }
}
