<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    protected static function booted()
    {
        static::saving(function () {
            cache()->forget('site_settings');
        });
    }

    public static function get($key, $default = null)
    {
        $settings = static::getAllSettings();
        return $settings[$key] ?? $default;
    }

    public static function getAllSettings()
    {
        return cache()->rememberForever('site_settings', function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }
}
