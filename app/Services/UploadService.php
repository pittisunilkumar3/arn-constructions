<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

/**
 * Upload Service - AMT Style Direct File Upload
 * 
 * Works exactly like AMT's Media_storage library:
 * - Files are saved directly to public/uploads/ directory
 * - No symlink needed (unlike Laravel's default Storage)
 * - Works perfectly on cPanel shared hosting
 * - Each subfolder has index.html to prevent directory listing
 * - Unique filenames to prevent overwrites
 */
class UploadService
{
    /**
     * The base upload path (relative to public/)
     * Like AMT's FCPATH . 'uploads/'
     */
    protected string $uploadBase;

    public function __construct()
    {
        // Use root-level uploads/ directory (like AMT's FCPATH . 'uploads/')
        // This works with both: root index.php + .htaccess setup (cPanel)
        $rootUploads = base_path('uploads');
        $publicUploads = public_path('uploads');

        // Prefer root-level uploads/ (for cPanel/XAMPP with root index.php)
        if (is_dir($rootUploads) && is_writable($rootUploads)) {
            $this->uploadBase = $rootUploads;
        } else {
            $this->uploadBase = $publicUploads;
        }

        $this->ensureDirectoryExists('');
    }

    /**
     * Upload a file to the specified subfolder
     * Like AMT's Media_storage::fileupload()
     *
     * @param UploadedFile $file The uploaded file
     * @param string $subfolder Subfolder inside uploads/ (e.g., 'projects', 'gallery')
     * @param string|null $customName Custom filename (optional)
     * @return string|null The stored file path relative to uploads/ (e.g., 'projects/1234-image.jpg')
     */
    public function upload(UploadedFile $file, string $subfolder = '', ?string $customName = null): ?string
    {
        $this->ensureDirectoryExists($subfolder);

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        if ($customName) {
            $filename = $customName;
        } else {
            // Generate unique filename like AMT: timestamp-uniqueid!originalname
            $filename = time() . '-' . uniqid() . '.' . $extension;
        }

        $relativePath = $subfolder ? "{$subfolder}/{$filename}" : $filename;
        $fullPath = $this->uploadBase . '/' . $relativePath;

        if ($file->move(dirname($fullPath), $filename)) {
            return $relativePath;
        }

        return null;
    }

    /**
     * Upload multiple files
     *
     * @param array $files Array of UploadedFile instances
     * @param string $subfolder Subfolder inside uploads/
     * @return array Array of stored file paths
     */
    public function uploadMultiple(array $files, string $subfolder = ''): array
    {
        $paths = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $path = $this->upload($file, $subfolder);
                if ($path) {
                    $paths[] = $path;
                }
            }
        }
        return $paths;
    }

    /**
     * Delete a file
     * Like AMT's Media_storage::filedelete()
     *
     * @param string|null $path File path relative to uploads/
     * @return bool
     */
    public function delete(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        $fullPath = $this->uploadBase . '/' . $path;

        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * Delete multiple files
     *
     * @param array $paths Array of file paths relative to uploads/
     * @return void
     */
    public function deleteMultiple(array $paths): void
    {
        foreach ($paths as $path) {
            $this->delete($path);
        }
    }

    /**
     * Check if a file exists
     *
     * @param string|null $path File path relative to uploads/
     * @return bool
     */
    public function exists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        return file_exists($this->uploadBase . '/' . $path);
    }

    /**
     * Get the full filesystem path
     *
     * @param string $path File path relative to uploads/
     * @return string
     */
    public function fullPath(string $path): string
    {
        return $this->uploadBase . '/' . $path;
    }

    /**
     * Get the file size
     *
     * @param string $path File path relative to uploads/
     * @return int|false
     */
    public function size(string $path): int|false
    {
        $fullPath = $this->uploadBase . '/' . $path;
        return file_exists($fullPath) ? filesize($fullPath) : false;
    }

    /**
     * Ensure a directory exists with proper permissions and index.html protection
     * Like AMT's approach of having index.html in every upload folder
     *
     * @param string $subfolder
     * @return void
     */
    protected function ensureDirectoryExists(string $subfolder): void
    {
        $path = $this->uploadBase;
        
        if ($subfolder) {
            $path .= '/' . $subfolder;
        }

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        // Create index.html to prevent directory listing (like AMT)
        $indexPath = $path . '/index.html';
        if (!file_exists($indexPath)) {
            file_put_contents($indexPath, '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
        }
    }

    /**
     * Get list of files in a subfolder
     *
     * @param string $subfolder
     * @return array
     */
    public function listFiles(string $subfolder = ''): array
    {
        $path = $this->uploadBase . ($subfolder ? '/' . $subfolder : '');
        
        if (!is_dir($path)) {
            return [];
        }

        return array_values(array_filter(
            scandir($path),
            fn($f) => !in_array($f, ['.', '..', 'index.html', '.gitignore', '.DS_Store'])
        ));
    }

    /**
     * Create all necessary upload directories
     * Called during installation
     * Creates both root-level (cPanel) and public/ (artisan serve) uploads
     *
     * @return void
     */
    public static function createUploadDirectories(): void
    {
        $dirs = [
            'projects',
            'projects/gallery',
            'gallery',
            'sliders',
            'testimonials',
            'amenities',
            'floor-plans',
            'brochures',
            'settings',
        ];

        // Create in root-level uploads/ (for cPanel/XAMPP with root index.php)
        $service = new self();
        foreach ($dirs as $dir) {
            $service->ensureDirectoryExists($dir);
        }

        // Also create in public/uploads/ (for artisan serve / standard Laravel)
        $publicBase = public_path('uploads');
        if (!is_dir($publicBase)) {
            mkdir($publicBase, 0755, true);
        }
        $indexHtml = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
        foreach ($dirs as $dir) {
            $path = $publicBase . '/' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            if (!file_exists($path . '/index.html')) {
                file_put_contents($path . '/index.html', $indexHtml);
            }
        }
        if (!file_exists($publicBase . '/index.html')) {
            file_put_contents($publicBase . '/index.html', $indexHtml);
        }
    }
}
