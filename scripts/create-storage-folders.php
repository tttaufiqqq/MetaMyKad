<?php

declare(strict_types=1);

$root = dirname(__DIR__);

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
    if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
        fwrite(STDERR, "Failed to create {$directory}\n");
        exit(1);
    }

    $gitkeep = $path . DIRECTORY_SEPARATOR . '.gitkeep';
    if (!file_exists($gitkeep)) {
        file_put_contents($gitkeep, '');
    }

    echo "OK {$directory}\n";
}

