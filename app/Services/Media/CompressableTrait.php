<?php

namespace app\Services\Media;

use Symfony\Component\HttpFoundation\File\UploadedFile;

trait CompressableTrait
{
    /**
     * Check whether the uploaded file is an image that can be compressed.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return bool
     */
    protected function canBeCompressed(UploadedFile $file)
    {
        return in_array($file->getClientMimeType(), [
            'image/jpeg',
            'image/png',
        ]);
    }
}
