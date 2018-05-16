<?php

namespace App\Services\Media;

use App\Models\Container;
use App\Services\MediaService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateService
{
    use CompressableTrait;

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
     * The media service instance.
     *
     * @var \App\Services\MediaService
     */
    protected $mediaService;

    /**
     * CreateService constructor.
     *
     * @param \App\Services\MediaService                          $mediaService
     * @param \App\Models\Container                               $container
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return void
     */
    public function __construct(MediaService $mediaService, Container $container, UploadedFile $file)
    {
        $this->mediaService = $mediaService;
        $this->container    = $container;
        $this->file         = $file;
        $this->filePath     = $this->generateValidFilePath();
        $this->fileName     = $this->generateValidFileName();
    }

    /**
     * Generate a random alpha string for the given length.
     *
     * @param int  $length
     * @param bool $includeNumbers
     *
     * @return string
     */
    protected function generateRandomAlphaString($length = 10, $includeNumbers = false)
    {
        $string     = '';
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = $includeNumbers ? $characters.'0123456789' : $characters;
        $maxLen     = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $maxLen)];
        }

        return $string;
    }

    /**
     * Generate a valid filepath to store a file for the given container.
     *
     * @return string
     */
    protected function generateValidFilePath()
    {
        return removeDoubleForwardSlash(
            'images/'.$this->generateRandomAlphaString(2)
        );
    }

    /**
     * Generate a valid filename to store a file for the given file path.
     *
     * @return string
     */
    protected function generateValidFileName()
    {
        $fileExtension = $this->generateValidExtension();
        do {
            $randomString = $this->generateRandomAlphaString(10, true);
            $fullPath     = "{$this->filePath}/{$randomString}{$fileExtension}";
        } while (file_exists($fullPath));

        return $randomString.$fileExtension;
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
     * Store the image in storage.
     *
     * @return \App\Models\Media
     */
    public function save()
    {
        $this->file->move(
            public_path($this->filePath), $this->fileName
        );

        $mediaItem = $this->mediaService->create([
            'name'         => $this->fileName,
            'container_id' => $this->container->id,
            'path'         => "$this->filePath/$this->fileName",
            'meta_data'    => [
                'can_be_compressed'   => $this->canBeCompressed($this->file),
                'has_been_compressed' => false,
                'file_mime'           => $this->file->getClientMimeType(),
            ],
        ]);

        // Schedule job to compress the media file
        if ($this->canBeCompressed($this->file)) {
            // Compress the original image
            $job = (new \App\Jobs\Media\CompressMediaJob($mediaItem));
            dispatch($job);

            // Create compressed versions of the image
            $job = (new \App\Jobs\Media\CreateOptimisedCopiesMediaJob($mediaItem));
            dispatch($job);
        }

        return $mediaItem;
    }
}
