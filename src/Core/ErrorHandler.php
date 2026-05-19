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
        $line = sprintf(
            "[%s] %s in %s:%d\n",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
        file_put_contents($logPath, $line, FILE_APPEND);

        http_response_code(500);
        require src_path('Views/errors/500.php');
    }
}
