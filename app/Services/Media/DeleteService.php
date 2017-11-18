<?php

namespace App\Services\Media;

use App\Models\Media;
use App\Repositories\Contracts\MediaRepositoryInterface;

class DeleteService
{
    /**
     * The media model instance.
     *
     * @var \App\Models\Media
     */
    protected $media;

    /**
     * The media repository instance.
     *
     * @var \App\Repositories\Contracts\MediaRepositoryInterface
     */
    protected $mediaRepository;

    /**
     * DeleteService constructor.
     *
     * @param \App\Models\Media                                    $media
     * @param \App\Repositories\Contracts\MediaRepositoryInterface $mediaRepository
     * @return void
     */
    function __construct(Media $media, MediaRepositoryInterface $mediaRepository)
    {
        $this->media           = $media;
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * Delete the media item from storage and queue a job to delete the physical file
     * in storage.
     *
     * @return \App\Models\Media
     */
    public function delete()
    {
        $this->mediaRepository->delete($this->media);

        // Delete file

        return $this->media;
    }
}
