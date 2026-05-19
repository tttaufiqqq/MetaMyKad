<?php

declare(strict_types=1);

namespace MetaMyKad\Core;

final class Auth
{
    public static function check(): bool
    {
        return Session::get('user') !== null;
    }

    public static function user(): ?array
    {
        return Session::get('user');
    }
}
