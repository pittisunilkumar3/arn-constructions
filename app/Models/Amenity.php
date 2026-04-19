<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $fillable = [
        'name', 'icon', 'description', 'project_id', 'is_active', 'sort_order'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
