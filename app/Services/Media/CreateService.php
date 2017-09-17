<?php

namespace App\Services\Media;

use App\Models\Media;
use App\Models\Container;
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
     * The filename that the media item will be stored as.
     *
     * @var string
     */
    protected $fileName;

    /**
     * The filepath that the media item will be stored in.
     *
     * @var string
     */
    protected $filePath;

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
        $this->filePath        = $this->generateValidFilePath();
        $this->fileName        = $this->generateValidFileName();
    }

    /**
     * Generate a random alpha string for the given length.
     *
     * @param int $length
     * @return string
     */
    protected function generateRandomAlphaString($length = 10)
    {
        return substr(md5(microtime()), rand(0, 26), $length);
    }

    /**
     * Generate a valid filepath to store a file for the given container.
     *
     * @return string
     */
    protected function generateValidFilePath()
    {
        return $this->generateRandomAlphaString(2);
    }

    /**
     * Generate a valid filename to store a file for the given file path.
     *
     * @return string
     */
    protected function generateValidFileName()
    {
        $randomString  = $this->generateRandomAlphaString(10);
        $fileExtension = $this->generateValidExtension();

        return $randomString . $fileExtension;
    }

    /**
     * Return an extension based on the file's mime or original extension.
     *
     * @return string
     */
    protected function generateValidExtension()
    {
        switch ($this->file->getClientMimeType()) {
            case 'image/jpeg':
                return '.jpg';
                break;
            case 'image/png':
                return '.png';
                break;
            default:
                return ".{$this->file->getClientOriginalExtension()}";
                break;
        }
    }

    /**
     * Check whether the uploaded file is an image that can be compressed.
     *
     * @return bool
     */
    protected function canBeCompressed()
    {
        return in_array($this->file->getClientMimeType(), [
            'image/jpeg',
            'image/png',
            ]);
    }

    /**
     * Store the image in storage.
     *
     * @return \App\Models\Media
     */
    public function save()
    {
        $this->file->move(
            public_path("images/$this->filePath"), $this->fileName
        );

        $this->media->name = $this->fileName;
        $this->media->container()->associate($this->container);
        $this->media->path      = "/images/$this->filePath/$this->fileName";
        $this->media->meta_data = [
            'has_been_processed' => false,
            'can_be_compressed'  => $this->canBeCompressed(),
            'file_mime'          => $this->file->getClientMimeType(),
        ];

        $this->media->save();

        return $this->media;
    }
}
