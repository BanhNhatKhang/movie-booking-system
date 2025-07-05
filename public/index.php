<?php
session_start();
error_reporting(E_ALL & ~E_DEPRECATED);
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

$router = new Router();
require_once __DIR__ . '/../app/config/routes.php';

$router->dispatch($_SERVER['REQUEST_URI']);