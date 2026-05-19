<?php

declare(strict_types=1);

namespace MetaMyKad\Core;

use MetaMyKad\Controllers\BaseController;
use MetaMyKad\Middleware\AuthMiddleware;

final class Router
{
    private array $routes = [];

    public function add(string $method, string $path, string $handler, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method || $route['path'] !== $path) {
                continue;
            }

            $this->runMiddleware($route['middleware']);

            [$controllerName, $action] = explode('@', $route['handler'], 2);
            $class = 'MetaMyKad\\Controllers\\' . $controllerName;

            /** @var BaseController $controller */
            $controller = new $class();
            $controller->{$action}();
            return;
        }

        http_response_code(404);
        require src_path('Views/errors/404.php');
    }

    private function runMiddleware(array $middleware): void
    {
        foreach ($middleware as $item) {
            if ($item === 'auth') {
                (new AuthMiddleware())->handle();
            }
        }
    }
}
