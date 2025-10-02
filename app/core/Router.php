<?php

namespace App\Core;

class Router
{
    protected array $routes = [];

    public function add(string $method, string $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => strtoupper($method),
        ];
    }

    public function get(string $uri, $controller)
    {
        $this->add('GET', $uri, $controller);
    }

    public function post(string $uri, $controller)
    {
        $this->add('POST', $uri, $controller);
    }

    public function dispatch(string $uri)
    {
        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($this->matchRoute($route['uri'], $uri) && $route['method'] === $_SERVER['REQUEST_METHOD']) {
                $controller = $route['controller'];

                if (is_callable($controller)) {
                    $controller();
                    return;
                }

                if (is_array($controller)) {
                    [$class, $method] = $controller;

                    if (class_exists($class)) {
                        $controllerInstance = new $class();
                        if (method_exists($controllerInstance, $method)) {
                            $controllerInstance->$method();
                            return;
                        }
                    }
                }
            }
        }

        $this->abort(404);
    }

    private function matchRoute(string $routeUri, string $requestUri): bool
    {
        // Simple exact match for now
        return $routeUri === $requestUri;
    }

    protected function abort(int $code = 404)
    {
        http_response_code($code);
        require_once dirname(__DIR__) . "/Views/errors/{$code}.php";
        die();
    }
}
?>