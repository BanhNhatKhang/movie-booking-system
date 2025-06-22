<?php
namespace App\Core;

class Router
{
    protected $routes = [];

    public function __construct()
    {
        // require __DIR__ . '/../config/routes.php';
    }

    public function add($method, $uri, $action)
    {
        $this->routes[strtoupper($method)][$uri] = $action;
    }

    public function dispatch($requestUri)
    {
        $uri = parse_url($requestUri, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            [$controller, $method] = explode('@', $action);
            $controller = "App\\Controllers\\$controller";
            call_user_func([new $controller, $method]);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}