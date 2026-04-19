<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title', 'image', 'category', 'project_id', 'is_active', 'sort_order'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
