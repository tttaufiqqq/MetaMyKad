<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/Core/App.php';

MetaMyKad\Core\App::boot()->run();
