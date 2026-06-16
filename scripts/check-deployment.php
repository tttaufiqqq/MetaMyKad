<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$failed = false;

function check_line(bool $ok, string $message): void
{
    global $failed;
    echo ($ok ? '[OK] ' : '[FAIL] ') . $message . PHP_EOL;
    if (!$ok) {
        $failed = true;
    }
}

function load_env_file(string $path): array
{
    if (!file_exists($path)) {
        return [];
    }

    $env = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }

    return $env;
}

$env = load_env_file($root . DIRECTORY_SEPARATOR . '.env');

check_line(PHP_VERSION_ID >= 80200, 'PHP version is 8.2 or newer. Current: ' . PHP_VERSION);

foreach (['pdo', 'pdo_mysql', 'fileinfo', 'mbstring', 'gd', 'exif'] as $extension) {
    check_line(extension_loaded($extension), "PHP extension loaded: {$extension}");
}

check_line(file_exists($root . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php'), 'Composer dependencies installed: vendor/autoload.php exists');
check_line(file_exists($root . DIRECTORY_SEPARATOR . '.env'), '.env file exists');
check_line(file_exists($root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php'), 'public/index.php exists');
check_line(file_exists($root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'web.config'), 'public/web.config exists');
check_line(file_exists($root . DIRECTORY_SEPARATOR . '.htaccess'), 'Apache root .htaccess exists');
check_line(file_exists($root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . '.htaccess'), 'Apache public/.htaccess exists');

$appUrl = $env['APP_URL'] ?? '';
check_line($appUrl !== '', 'APP_URL is configured');
if ($appUrl !== '') {
    $path = parse_url($appUrl, PHP_URL_PATH) ?: '';
    check_line($path === '' || str_starts_with($path, '/'), 'APP_URL path is valid: ' . ($path ?: '/'));
}

$directories = [
    'storage/cache',
    'storage/logs',
    'storage/tmp',
    'storage/uploads',
    'storage/uploads/photo',
    'storage/uploads/audio',
    'storage/uploads/pdf',
    'storage/uploads/video',
];

foreach ($directories as $directory) {
    $path = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $directory);
    check_line(is_dir($path), "Directory exists: {$directory}");
    check_line(is_dir($path) && is_writable($path), "Directory writable: {$directory}");
}

if (extension_loaded('pdo_mysql') && $env !== []) {
    $host = $env['DB_HOST'] ?? '127.0.0.1';
    $port = (int) ($env['DB_PORT'] ?? 3306);
    $name = $env['DB_NAME'] ?? 'metamykad';
    $charset = $env['DB_CHARSET'] ?? 'utf8mb4';
    $user = $env['DB_USER'] ?? 'root';
    $pass = $env['DB_PASS'] ?? '';

    try {
        new PDO("mysql:host={$host};port={$port};dbname={$name};charset={$charset}", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        check_line(true, "Database connection works: {$name}");
    } catch (Throwable $e) {
        check_line(false, 'Database connection failed: ' . $e->getMessage());
    }
}

exit($failed ? 1 : 0);
