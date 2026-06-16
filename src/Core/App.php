<?php

declare(strict_types=1);

namespace MetaMyKad\Core;

require_once dirname(__DIR__) . '/Helpers/functions.php';

use MetaMyKad\Middleware\CSRFMiddleware;

final class App
{
    private static ?self $instance = null;
    private static array $config = [];

    private Router $router;

    private function __construct()
    {
        $this->loadEnv(base_path('.env'));
        self::$config = [
            'app' => require base_path('config/app.php'),
            'database' => require base_path('config/database.php'),
        ];

        date_default_timezone_set((string) self::$config['app']['timezone']);

        ErrorHandler::register();
        Session::start();

        $this->router = new Router();
        $this->registerRoutes(require base_path('config/routes.php'));
    }

    public static function boot(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function config(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return self::$config;
        }

        $segments = explode('.', $key);
        $value = self::$config;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    public function run(): void
    {
        ob_start();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            (new CSRFMiddleware())->handle();
        }

        $this->router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', current_path());

        ob_end_flush();
    }

    private function registerRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            [$method, $path, $handler, $middleware] = array_pad($route, 4, []);
            $this->router->add($method, $path, $handler, $middleware);
        }
    }

    private function loadEnv(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv($key . '=' . $value);
        }
    }
}
