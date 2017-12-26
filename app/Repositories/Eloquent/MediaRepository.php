<?php

namespace App\Repositories\Eloquent;

use App\Models\Media;
use App\Models\Container;
use App\Repositories\Contracts\MediaRepositoryInterface;

class MediaRepository extends AbstractBaseRepository implements MediaRepositoryInterface
{
    /**
     * The eloquent model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * MediaRepository constructor.
     *
     * @param \App\Models\Media $media
     */
    public function __construct(Media $media)
    {
        $this->model = $media;
    }

    /**
     * Get all media items belonging to the given container.
     *
     * @param \App\Models\Container $container
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMediaBelongingToContainer(Container $container)
    {
        return $this->where('container_id', '=', $container->id)->getQueryInstance()
            ->withCompressedCopies()->get();
    }

    /**
     * Get a media item belonging to a given collection.
     *
     * @param \App\Models\Container $container
     * @param int                   $mediaId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getMediaItemBelongingToContainer(Container $container, $mediaId)
    {
        return $this->where('container_id', '=', $container->id)->where('id', $mediaId)
            ->getQueryInstance()->withCompressedCopies()->first();
    }

}
