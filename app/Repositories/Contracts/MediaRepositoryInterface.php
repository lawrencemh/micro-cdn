<?php

namespace App\Repositories\Contracts;

use App\Models\Container;

interface MediaRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all media items belonging to the given container.
     *
     * @param \App\Models\Container $container
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMediaBelongingToContainer(Container $container);

    /**
     * Get a media item belonging to a given collection.
     *
     * @param \App\Models\Container $container
     * @param int                   $mediaId
     * @return \App\Models\Media
     */
    public function getMediaItemBelongingToContainer(Container $container, $mediaId);

}
