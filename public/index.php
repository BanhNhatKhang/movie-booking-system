<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');

$sessionPath = __DIR__ . '/../storage/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}
ini_set('session.save_path', $sessionPath);

session_start();

error_reporting(E_ALL & ~E_DEPRECATED);
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

$router = new Router();
require_once __DIR__ . '/../app/config/routes.php';

$router->dispatch($_SERVER['REQUEST_URI']);