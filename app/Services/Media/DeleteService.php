<?php

namespace App\Services\Media;

use App\Models\Media;
use App\Services\MediaService;
use App\Services\CompressedCopyService;
use App\Exceptions\Services\Media\FailedToRemoveFromStorageException;

class DeleteService
{
    /**
     * The compressed copy service instance.
     *
     * @var \App\Services\CompressedCopyService
     */
    protected $compressedCopyService;

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
     * @param \App\Services\CompressedCopyService $compressedCopyService
     * @param \App\Models\Media                   $media
     * @param \App\Services\MediaService          $mediaService
     *
     * @return void
     */
    public function __construct(CompressedCopyService $compressedCopyService, Media $media, MediaService $mediaService)
    {
        $this->compressedCopyService = $compressedCopyService;
        $this->media                 = $media;
        $this->mediaService          = $mediaService;
    }

    /**
     * Delete the media item from storage and queue a job to delete the physical file
     * in storage.
     *
     * @throws \App\Exceptions\Services\Media\FailedToRemoveFromStorageException
     *
     * @return \App\Models\Media
     */
    public function delete()
    {
        try {
            // Start DB transaction to allow a rollback in the instance the file fails to remove from storage.
            app('db')->beginTransaction();

            // Remove any compressed copies
            foreach ($this->media->compressedCopies as $copy) {
                $this->compressedCopyService->destroy($copy->id);
            }

            $this->mediaService->destroy($this->media->id);

            // Physically remove file from storage
            if (file_exists($this->media->getFullLocalPath()) && unlink($this->media->getFullLocalPath()) === false) {
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
}
