<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta_data' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'container_id',
        'name',
        'meta_data',
        'path',
    ];

    /**
     * Get the container that the media item belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function container()
    {
        return $this->belongsTo('App\Models\Container', 'container_id');
    }

    /**
     * Return the full public url to the media asset.
     *
     * @return string
     */
    public function getFullPublicPath()
    {
        return url($this->path);
    }

    /**
     * Return the full local path to the media asset.
     *
     * @return string
     */
    public function getFullLocalPath()
    {
        return public_path($this->path);
    }
}
