<?php

declare(strict_types=1);

namespace MetaMyKad\Middleware;

use MetaMyKad\Core\CSRF;

final class CSRFMiddleware
{
    public function handle(): void
    {
        if (!CSRF::verify($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            require src_path('Views/errors/403.php');
            exit;
        }
    }
}
