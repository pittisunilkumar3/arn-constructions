<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'type',
        'project_id', 'status', 'source', 'notes'
    ];

    protected $casts = [
        'project_id' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}
