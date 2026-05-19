<?php

declare(strict_types=1);

namespace MetaMyKad\Core;

final class Flash
{
    public static function set(string $type, string $message): void
    {
        Session::put('_flash', [
            'type' => $type,
            'message' => $message,
        ]);
    }

    public static function get(): ?array
    {
        $message = Session::get('_flash');
        Session::forget('_flash');
        return $message;
    }
}
