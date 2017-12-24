<?php

namespace App\Services;

use App\Models\Media;
use Eventviva\ImageResize as ImageResizer;

class ImageOptimiserService
{
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
     * The image resize service instance.
     *
     * @var \Eventviva\ImageResize
     */
    protected $imageResizer;

    /**
     * ImageOptimiserService constructor.
     *
     * @param \App\Models\Media               $media
     * @param \App\Services\MediaService|null $mediaService
     * @return void
     */
    public function __construct(Media $media, MediaService $mediaService = null)
    {
        $this->media        = $media;
        $this->mediaService = $mediaService ?? app(MediaService::class);
        $this->imageResizer = new ImageResizer($media->getFullLocalPath());
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
        $this->imageResizer->quality_jpg = $qualityArray['png'] ?? 6;
    }
}
