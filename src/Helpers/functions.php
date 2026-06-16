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

function fmt_date(?string $value): string
{
    if ($value === null || $value === '') {
        return '—';
    }
    $dt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value)
       ?: \DateTimeImmutable::createFromFormat('Y-m-d', $value);
    return $dt ? $dt->format('d/m/Y') : e($value);
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
        'Pendaftar' => ['color' => '#94a3b8', 'path' => '<circle cx="12" cy="8" r="5"/><path d="M8 13.5 7 22l5-3 5 3-1-8.5"/>'],
        'Pelajar'   => ['color' => '#38bdf8', 'path' => '<path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 2.2 2.7 4 6 4s6-1.8 6-4v-5"/><line x1="22" y1="10" x2="22" y2="15"/>'],
        'Aktif'     => ['color' => '#facc15', 'path' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>'],
        'Dedikasi'  => ['color' => '#f97316', 'path' => '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>'],
        'Cemerlang' => ['color' => '#eab308', 'path' => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>'],
    ];
    $entry = $map[$badge] ?? null;
    if ($entry === null) {
        return e($badge);
    }
    $svg = '<svg width="' . e($size) . '" height="' . e($size) . '" viewBox="0 0 24 24"'
         . ' fill="none" stroke="' . $entry['color'] . '" stroke-width="1.8"'
         . ' stroke-linecap="round" stroke-linejoin="round"'
         . ' aria-hidden="true" style="vertical-align:middle;flex-shrink:0;">'
         . $entry['path'] . '</svg>';
    return $svg . ' ' . e($badge);
}
