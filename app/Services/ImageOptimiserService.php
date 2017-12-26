<?php

namespace App\Services;

use App\Models\Media;
use Eventviva\ImageResize as ImageResizer;

class ImageOptimiserService
{
    /**
     * The compressed copy service instance.
     *
     * @var \App\Services\CompressedCopyService
     */
    protected $compressedCopyService;

    /**
     * The image resize service instance.
     *
     * @var \Eventviva\ImageResize
     */
    protected $imageResizer;

    /**
     * The media instance.
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
     * ImageOptimiserService constructor.
     *
     * @param \App\Models\Media                   $media
     * @param \App\Services\MediaService|null     $mediaService
     * @param \App\Services\CompressedCopyService $compressedCopyService
     * @return void
     */
    public function __construct(
        Media $media,
        MediaService $mediaService = null,
        CompressedCopyService $compressedCopyService = null
    ) {
        $this->imageResizer          = new ImageResizer($media->getFullLocalPath());
        $this->compressedCopyService = $compressedCopyService ?? app(CompressedCopyService::class);
        $this->media                 = $media;
        $this->mediaService          = $mediaService ?? app(MediaService::class);
    }

    /**
     * Append a suffix to the filename for the given path string.
     *
     * @param string $path
     * @param string $suffix
     * @return string
     */
    protected function addsuffixToFileNameFromPath($path, $suffix = '')
    {
        $fileName = pathinfo($path, PATHINFO_FILENAME);
        $ext      = pathinfo($path, PATHINFO_EXTENSION);
        $path     = pathinfo($path, PATHINFO_DIRNAME);

        return "{$path}/{$fileName}{$suffix}.{$ext}";
    }

    /**
     * Compress the given media item's original image file to the specified
     * quality levels set in the .env
     *
     * @return $this
     */
    public function compress()
    {
        $quality = (int) env('ORIGINAL_IMAGE_QUALITY', 100);

        if (is_null($quality) || $quality === 100) {
            return $this;
        }

        // Set quality levels
        $this->setQualityLevels($quality);

        // Save image compressed
        $this->imageResizer->save($this->media->getFullLocalPath());

        // Update media's meta_data
        $this->mediaService->update($this->media)
            ->addOrUpdateUserMetaData(['has_been_compressed' => true], true)->save();

        return $this;
    }


    /**
     * Attempt to create the compressed copies for the media item.
     *
     * @return $this
     */
    public function createCompressedCopies()
    {
        $quality = (int) env('ORIGINAL_IMAGE_QUALITY', 100);

        // Set quality levels
        $this->setQualityLevels($quality);

        app('db')->transaction(function () {
            // Create and associate the small copy of the image
            if (is_null($this->media->small)) {
                $this->media->small()->save(
                    $this->createCompressedCopy(env('FIT_SMALL_COPIES_TO_PX', 100), '_S', 'sm')
                );
            }

            // Create and associate the medium copy of the image
            if (is_null($this->media->medium)) {
                $this->media->medium()->save(
                    $this->createCompressedCopy(env('FIT_MEDIUM_COPIES_TO_PX', 500), '_M', 'm')
                );
            }

            // Create and associate the medium copy of the image
            if (is_null($this->media->large)) {
                $this->media->large()->save(
                    $this->createCompressedCopy(env('FIT_LARGE_COPIES_TO_PX', 1000), '_LG', 'lg')
                );
            }
        });

        return $this;
    }

    /**
     * Create the compressed copy image in storage and create & return a new compressed copy entity
     * in the database.
     *
     * @param int    $maxLongestSide
     * @param string $fileSuffix
     * @param string $type
     * @return \App\Models\CompressedCopy
     */
    protected function createCompressedCopy($maxLongestSide, $fileSuffix, $type)
    {
        // Create the image
        $imagePath = $this->createResizedCopyOfImage($maxLongestSide, $fileSuffix);

        // Create and return the entity in storage
        return $this->compressedCopyService->create([
            'media_id' => $this->media->id,
            'type'     => $type,
            'name'     => $this->getFileNameAndExtensionFromPath($imagePath),
            'path'     => $imagePath,
        ]);
    }

    /**
     * Create and save a copy of the image for the given max length in pixels and
     * file suffix.
     *
     * @param int    $maxLongestSide
     * @param string $fileSuffix
     * @return string
     */
    protected function createResizedCopyOfImage($maxLongestSide = 1000, $fileSuffix = 'lg')
    {
        // Clone the image resizer with config and file reference
        $image = clone $this->imageResizer;

        $image->resizeToBestFit($maxLongestSide, $maxLongestSide);

        $newPath = $this->addsuffixToFileNameFromPath($this->media->path, $fileSuffix);
        $image->save(public_path($newPath));

        return $newPath;
    }

    /**
     * Return the filename and extension for the given path.
     *
     * @param string $path
     * @return string
     */
    protected function getFileNameAndExtensionFromPath($path)
    {
        return pathinfo($path, PATHINFO_FILENAME) . '.' . pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Set the quality levels based on the given quality level.
     *
     * @param int $quality
     * @return void
     */
    protected function setQualityLevels($quality)
    {
        // Set quality levels
        $qualityArray = $this->getQualityLevels($quality);

        $this->imageResizer->quality_jpg = $qualityArray['jpg'] ?? 100;
        $this->imageResizer->quality_png = $qualityArray['png'] ?? 6;
    }

    /**
     * get the quality for jpg and png respectively based on the given quality out of 100.
     *
     * @param int $quality
     * @return array
     */
    protected function getQualityLevels($quality): array
    {
        $percentage = $quality / 100;

        return [
            'jpg' => ceil(100 * $percentage),
            'png' => ceil(6 * $percentage),
        ];
    }
}
