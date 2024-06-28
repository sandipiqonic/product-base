<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alias',
        'description',
        'version',
        'status',
        'parent_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'string',
    ];

     /**
     * Get the parent plugin.
     */
    public function parent()
    {
        return $this->belongsTo(Plugin::class, 'parent_id');
    }

    /**
     * Get the child plugins.
     */
    public function children()
    {
        return $this->hasMany(Plugin::class, 'parent_id');
    }
}

