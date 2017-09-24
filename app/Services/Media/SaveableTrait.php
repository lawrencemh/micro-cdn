<?php

namespace app\Services\Media;

trait SaveableTrait
{
    /**
     * Save the media item.
     *
     * @return $this
     */
    public function save()
    {
        $this->media->save();

        return $this;
    }

    public function saveAndRetrieve()
    {
        $this->save();

        return $this->media;
    }
}
