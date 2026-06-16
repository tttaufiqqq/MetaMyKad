<?php

declare(strict_types=1);

/**
 * Root entry point — forwards to the public front controller.
 *
 * This file exists so the project can be accessed at /metamykad/ when
 * deployed inside a parent directory (e.g. XAMPP htdocs) without needing
 * to configure Apache's DocumentRoot to point at public/.
 *
 * The root .htaccess handles mod_rewrite-based routing automatically;
 * this file is the fallback when mod_rewrite is unavailable.
 */
require __DIR__ . '/public/index.php';
