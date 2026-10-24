<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Cek mode maintenance
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Load Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap aplikasi Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());