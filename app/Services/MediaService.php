<?php

namespace App\Services;

use App\Models\Container;
use App\Services\Media\CreateService;
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
    public function create(Container $container ,UploadedFile $file)
    {
        return new CreateService(app(MediaRepositoryInterface::class), $container, $file);
    }
}
