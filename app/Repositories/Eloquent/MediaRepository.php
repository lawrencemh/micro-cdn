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
}