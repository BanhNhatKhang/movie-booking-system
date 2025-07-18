<?php
namespace App\Core;

class Router 
{
    private $routes = [];

    public function add($method, $path, $handler) 
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($requestUri) 
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($requestUri, PHP_URL_PATH);
        
        error_log("Router dispatch - Method: $method, Path: $path");
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            // Exact match trước
            if ($route['path'] === $path) {
                error_log("Exact match found for: $path");
                $this->callHandler($route['handler']);
                return;
            }
            
            if ($this->matchDynamicRoute($route['path'], $path)) {
                error_log("Dynamic match found for: $path with pattern: {$route['path']}");
                $this->callHandler($route['handler']);
                return;
            }
        }
        
        error_log("No route found for: $path");
        http_response_code(404);
        echo "404 Not Found";
    }

    /**
     * Kiểm tra dynamic route matching
     */
    private function matchDynamicRoute($pattern, $path) 
    {
        // Chuyển đổi pattern như /phim/{slug} thành regex
        $regexPattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $pattern);
        $regexPattern = '#^' . $regexPattern . '$#';
        
        if (preg_match($regexPattern, $path, $matches)) {
            // Lưu parameters vào $_GET
            if ($pattern === '/phim/{slug}') {
                $_GET['slug'] = $matches[1];
                error_log("Captured phim slug: " . $matches[1]);
            } elseif ($pattern === '/chon-ghe/{showtime_slug}') {
                $_GET['showtime_slug'] = $matches[1];
                error_log("Captured showtime slug: " . $matches[1]);
            }
            return true;
        }
        
        return false;
    }

    private function callHandler($handler) 
    {
        if (is_string($handler)) {
            list($controller, $method) = explode('@', $handler);
            $controllerClass = "App\\Controllers\\$controller";
            
            if (class_exists($controllerClass)) {
                $instance = new $controllerClass();
                if (method_exists($instance, $method)) {
                    call_user_func([$instance, $method]);
                } else {
                    throw new \Exception("Method $method not found in $controllerClass");
                }
            } else {
                throw new \Exception("Controller $controllerClass not found");
            }
        }
    }
}