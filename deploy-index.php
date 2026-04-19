<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Point to the Laravel app directory OUTSIDE public_html
// Change 'anr-website' to match your folder name on the server
if (! defined('LARAVEL_BASE_PATH')) {
    define('LARAVEL_BASE_PATH', dirname(__DIR__).'/anr-website');
}

// Load Composer autoloader
require LARAVEL_BASE_PATH.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once LARAVEL_BASE_PATH.'/bootstrap/app.php';

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
