<?php

/**
 * Global Helper Functions for ANR Constructions
 * Provides AMT-style upload URL helpers and utility functions
 */

use App\Models\SiteSetting;
use App\Services\UploadService;

if (!function_exists('upload_url')) {
    /**
     * Generate URL for an uploaded file (AMT-style, like base_url() . 'uploads/')
     * 
     * Usage: upload_url('projects/1234-image.jpg')
     * Returns: https://yourdomain.com/uploads/projects/1234-image.jpg
     *
     * @param string|null $path File path relative to uploads/ directory
     * @return string
     */
    function upload_url(?string $path = null): string
    {
        if (empty($path)) {
            return asset('uploads');
        }

        // If path already contains full URL, return as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('uploads/' . $path);
    }
}

if (!function_exists('upload_base_path')) {
    /**
     * Get the filesystem base path for uploads directory
     * Checks root-level first (cPanel), then public/ (artisan serve)
     *
     * @return string
     */
    function upload_base_path(): string
    {
        $rootUploads = base_path('uploads');
        if (is_dir($rootUploads) && is_writable($rootUploads)) {
            return $rootUploads;
        }
        return public_path('uploads');
    }
}

if (!function_exists('upload_path')) {
    /**
     * Get the full filesystem path for an uploaded file
     * Like AMT's FCPATH . 'uploads/'
     *
     * @param string|null $path File path relative to uploads/
     * @return string
     */
    function upload_path(?string $path = null): string
    {
        $basePath = upload_base_path();
        
        if ($path) {
            return $basePath . '/' . $path;
        }
        
        return $basePath;
    }
}

if (!function_exists('upload_exists')) {
    /**
     * Check if an uploaded file exists
     *
     * @param string|null $path File path relative to uploads/
     * @return bool
     */
    function upload_exists(?string $path = null): bool
    {
        if (empty($path)) {
            return false;
        }
        return file_exists(upload_base_path() . '/' . $path);
    }
}

if (!function_exists('upload_delete')) {
    /**
     * Delete an uploaded file
     *
     * @param string|null $path File path relative to uploads/
     * @return bool
     */
    function upload_delete(?string $path = null): bool
    {
        if (empty($path)) {
            return false;
        }

        $fullPath = upload_base_path() . '/' . $path;
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }
}

if (!function_exists('upload_delete_multiple')) {
    /**
     * Delete multiple uploaded files
     *
     * @param array $paths Array of file paths relative to uploads/
     * @return void
     */
    function upload_delete_multiple(array $paths): void
    {
        foreach ($paths as $path) {
            upload_delete($path);
        }
    }
}

if (!function_exists('image_or_fallback')) {
    /**
     * Get image URL with fallback to a default image
     * Like AMT's pattern: if image exists show it, else show no_image.png
     *
     * @param string|null $path File path relative to uploads/
     * @param string $fallback Fallback image path relative to public/
     * @return string
     */
    function image_or_fallback(?string $path = null, string $fallback = 'images/hero-default.jpg'): string
    {
        if (!empty($path) && upload_exists($path)) {
            return upload_url($path);
        }

        return asset($fallback);
    }
}

if (!function_exists('no_image_url')) {
    /**
     * Get the default no-image placeholder URL
     * Like AMT's uploads/no_image.png
     *
     * @return string
     */
    function no_image_url(): string
    {
        return asset('images/hero-default.jpg');
    }
}

if (!function_exists('site_setting')) {
    /**
     * Quick helper to get a site setting value
     * Like AMT's $this->setting_model->getSetting()
     *
     * @param string $key Setting key
     * @param mixed $default Default value
     * @return mixed
     */
    function site_setting(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }
}

if (!function_exists('format_price')) {
    /**
     * Format price in Indian format
     *
     * @param float|null $price
     * @return string
     */
    function format_price(?float $price): string
    {
        if ($price === null) {
            return 'Price on Request';
        }

        if ($price >= 10000000) {
            return '₹' . number_format($price / 10000000, 2) . ' Cr';
        }

        if ($price >= 100000) {
            return '₹' . number_format($price / 100000, 2) . ' L';
        }

        return '₹' . number_format($price);
    }
}

if (!function_exists('create_upload_dirs')) {
    /**
     * Create all necessary upload directories with index.html protection
     * Called during installation (like AMT's folder structure)
     *
     * @return void
     */
    function create_upload_dirs(): void
    {
        UploadService::createUploadDirectories();
    }
}
