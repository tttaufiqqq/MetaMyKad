<?php

declare(strict_types=1);

use MetaMyKad\Core\App;

function config(?string $key = null, mixed $default = null): mixed
{
    return App::config($key, $default);
}

function base_path(string $path = ''): string
{
    $base = dirname(__DIR__, 2);
    return $path === '' ? $base : $base . '/' . ltrim($path, '/');
}

function public_path(string $path = ''): string
{
    return base_path('public/' . ltrim($path, '/'));
}

function storage_path(string $path = ''): string
{
    return base_path('storage/' . ltrim($path, '/'));
}

function src_path(string $path = ''): string
{
    return base_path('src/' . ltrim($path, '/'));
}

function url(string $path = ''): string
{
    $baseUrl = rtrim((string) config('app.base_url', ''), '/');
    $normalized = ltrim($path, '/');

    return $normalized === '' ? $baseUrl : $baseUrl . '/' . $normalized;
}

function app_base_path(): string
{
    $path = parse_url((string) config('app.base_url', ''), PHP_URL_PATH);
    if (!is_string($path) || trim($path, '/') === '') {
        return '';
    }

    return '/' . trim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . url(ltrim($path, '/')));
    exit;
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function current_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $path = is_string($path) && $path !== '' ? $path : '/';
    $basePath = app_base_path();

    if ($basePath !== '' && ($path === $basePath || str_starts_with($path, $basePath . '/'))) {
        $path = substr($path, strlen($basePath));
    }

    $path = '/' . trim($path, '/');
    return $path === '/' ? '/' : $path;
}

function badge_icon(string $badge, string $size = '1.3rem'): string
{
    static $map = [
        'Pendaftar' => 'pendaftar.png',
        'Pelajar'   => 'pelajar.png',
        'Aktif'     => 'aktif.png',
        'Dedikasi'  => 'dedikasi.png',
        'Cemerlang' => 'cemerlang.png',
    ];
    $file = $map[$badge] ?? null;
    if ($file === null) {
        return e($badge);
    }
    $src = asset('images/nav/' . $file);
    return '<img src="' . e($src) . '" alt="' . e($badge) . '" title="' . e($badge) . '"'
         . ' style="width:' . e($size) . ';height:' . e($size) . ';object-fit:contain;vertical-align:middle;" loading="lazy">'
         . ' ' . e($badge);
}
