<?php

declare(strict_types=1);

namespace MetaMyKad\Core;

use Throwable;

final class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler(static function (int $severity, string $message, string $file, int $line): void {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });
    }

    public static function handleException(Throwable $exception): void
    {
        $logPath = storage_path('logs/app.log');
        $entry   = sprintf(
            "[%s] [%s] %s in %s:%d\nStack trace:\n%s\n---\n",
            date('Y-m-d H:i:s'),
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );

        $logsDir = storage_path('logs');
        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }
        file_put_contents($logPath, $entry, FILE_APPEND);

        $debug     = (bool) filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $errorCode = $exception->getCode();

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if (!headers_sent()) {
            http_response_code(500);
        }
        require src_path('Views/errors/500.php');
    }
}
