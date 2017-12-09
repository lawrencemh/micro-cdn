<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'containers';
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'user',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id',
    ];
    
    /**
     * Get the user that the container belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get all the media items that belong to the container.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany('App\Models\Media', 'container_id');
    }
}
