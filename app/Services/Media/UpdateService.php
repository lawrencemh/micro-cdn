<?php

namespace App\Services\Media;

use App\Models\Media;

class UpdateService
{
    use SaveableTrait;

    /**
     * The keys that users are forbidden to add to meta_data.
     *
     * @var array
     */
    protected $forbiddenUserMetaKeys = [
        'file_mime',
        'can_be_compressed',
        'has_been_compressed',
    ];

    /**
     * The media model instance.
     *
     * @var \App\Models\Media
     */
    protected $media;

    /**
     * UpdateService constructor.
     *
     * @param \App\Models\Media $media
     *
     * @return void
     */
    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    /**
     * Add or update existing user meta_data for the given media item.
     *
     * @param array $array
     * @param bool  $ignoreProtectedKeyCheck
     *
     * @return $this
     */
    public function addOrUpdateUserMetaData($array = [], $ignoreProtectedKeyCheck = false)
    {
        foreach ($array as $key => $value) {
            if (!in_array($key, $this->forbiddenUserMetaKeys) || $ignoreProtectedKeyCheck) {

                // if the value is null or empty unset from meta_data, else add/update the value
                if (is_null($value) || empty($value)) {
                    $metaData = $this->media->meta_data;
                    unset($metaData[$key]);
                    $this->media->meta_data = $metaData;
                } else {
                    $this->media->meta_data = array_merge($this->media->meta_data, [$key => $value]);
                }
            }
        }

        return $this;
    }
}
