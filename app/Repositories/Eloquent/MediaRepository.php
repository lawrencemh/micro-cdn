<?php

namespace App\Repositories\Eloquent;

use App\Models\Media;
use App\Models\Container;
use App\Repositories\Contracts\MediaRepositoryInterface;

class MediaRepository implements MediaRepositoryInterface
{
    /**
     * The media instance.
     *
     * @var \App\Models\Media
     */
    protected $media;

    /**
     * MediaRepository constructor.
     *
     * @param \App\Models\Media $media
     */
    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    /**
     * Get all media items belonging to the given container.
     *
     * @param \App\Models\Container $container
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMediaBelongingToContainer(Container $container)
    {
        return $this->media->where('container_id', $container->id)->get();
    }

    /**
     * Get a media item belonging to a given collection.
     *
     * @param \App\Models\Container $container
     * @param int $mediaId
     * @return \App\Models\Media
     */
    public function getMediaItemBelongingToContainer(Container $container, $mediaId)
    {
        return $this->media->where('container_id', $container->id)
            ->where('id', $mediaId)
            ->firstOrFail();
    }

    /**
     * Save the given media item in storage.
     *
     * @param \App\Models\Media $media
     * @return bool
     */
    public function save(Media $media)
    {
        return $media->save();
    }

    /**
     * Delete the given media item from storage.
     *
     * @param \App\Models\Media $media
     * @return bool
     */
    public function delete(Media $media)
    {
        return $media->delete();
    }
}
