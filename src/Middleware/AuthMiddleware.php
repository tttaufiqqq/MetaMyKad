<?php

declare(strict_types=1);

namespace MetaMyKad\Middleware;

use MetaMyKad\Core\Auth;
use MetaMyKad\Core\Flash;

final class AuthMiddleware
{
    public function handle(): void
    {
        if (!Auth::check()) {
            Flash::set('warning', 'Please sign in first.');
            redirect('/');
        }
    }
}
