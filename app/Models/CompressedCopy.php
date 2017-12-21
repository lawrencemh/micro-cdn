<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;

class CompressedCopy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'compressed_copies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'media_id',
        'type',
        'name',
        'path',
    ];

    /**
     * The media item this compressed copy belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function media()
    {
        return $this->belongsTo(\App\Models\Media::class);
    }
}
