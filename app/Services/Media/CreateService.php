<?php

namespace App\Services\Media;

use App\Models\Container;
use App\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repositories\Contracts\MediaRepositoryInterface;

class CreateService
{
    /**
     * The container instance.
     *
     * @var \App\Models\Container
     */
    protected $container;

    /**
     * The file instance.
     *
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected $file;

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
     * CreateService constructor.
     *
     * @param \App\Repositories\Contracts\MediaRepositoryInterface $mediaRepository
     * @param \App\Models\Container                                $container
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile  $file
     * @return void
     */
    public function __construct(MediaRepositoryInterface $mediaRepository, Container $container, UploadedFile $file)
    {
        $this->mediaRepository = $mediaRepository;
        $this->container       = $container;
        $this->file            = $file;
        $this->media           = new Media;
    }

    /**
     * Generate a valid filepath to store a file for the given container.
     *
     * @return string
     */
    protected function generateValidFilePath()
    {
        // @todo
    }

    /**
     * Generate a valid filename to store a file for the given file path.
     *
     * @param string $filePath
     * @return string
     */
    protected function generateValidFileName($filePath = '')
    {
        // @todo
    }

    /**
     * Store the image in storage.
     *
     * @return $this
     */
    public function store()
    {
        // @todo wip
        $this->file->move(storage_path('images/test'));

        return $this;
    }
}
