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
        return $this->belongsTo(\App\Models\Container::class, 'container_id');
    }

    /**
     * Return the compressed copies the media item has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function compressedCopies()
    {
        return $this->hasMany(\App\Models\CompressedCopy::class);
    }

    /**\
     * Get the small version of the image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function small()
    {
        return $this->hasOne(\App\Models\CompressedCopy::class)->where('type', 'sm');
    }

    /**
     * Get the medium version of the image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function medium()
    {
        return $this->hasOne(\App\Models\CompressedCopy::class)->where('type', 'm');
    }

    /**
     * Get the large version of the image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function large()
    {
        return $this->hasOne(\App\Models\CompressedCopy::class)->where('type', 'lg');
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

    /**
     * Returns true if the media item can be compressed.
     *
     * @return bool
     */
    public function canBeCompressed()
    {
        return isset($this->meta_data['can_be_compressed']) ? (bool) $this->meta_data['can_be_compressed'] : false;
    }
}
