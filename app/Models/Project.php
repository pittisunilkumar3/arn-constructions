<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'name', 'slug', 'location', 'city', 'short_description', 'description',
        'type', 'status', 'price_min', 'price_max', 'bhk_options', 'total_units',
        'area_min', 'area_max', 'rera_id', 'possession_date', 'featured_image',
        'gallery', 'video_url', 'brochure', 'highlights', 'is_featured',
        'is_active', 'latitude', 'longitude'
    ];

    protected $casts = [
        'gallery' => 'array',
        'highlights' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price_min' => 'decimal:2',
        'price_max' => 'decimal:2',
        'area_min' => 'decimal:2',
        'area_max' => 'decimal:2',
        'possession_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });

        // Only auto-generate slug on update if slug is empty/not provided
        static::updating(function ($project) {
            if ($project->isDirty('name') && empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });
    }

    public function amenities()
    {
        return $this->hasMany(Amenity::class);
    }

    public function floorPlans()
    {
        return $this->hasMany(FloorPlan::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getPriceRangeAttribute()
    {
        if ($this->price_min && $this->price_max) {
            return '₹' . number_format($this->price_min / 100000, 1) . 'L - ₹' . number_format($this->price_max / 100000, 1) . 'L';
        }
        return 'Price on Request';
    }

    public function getAreaRangeAttribute()
    {
        if ($this->area_min && $this->area_max) {
            return $this->area_min . ' - ' . $this->area_max . ' sq.ft';
        }
        return '';
    }
}
