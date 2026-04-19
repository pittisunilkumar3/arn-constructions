<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Maintenance mode
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register autoloader
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel - set public path to THIS directory (root)
$app = require_once __DIR__.'/bootstrap/app.php';

// Override public path to root for cPanel/XAMPP
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
