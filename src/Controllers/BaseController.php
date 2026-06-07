<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Core\Flash;

abstract class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        $contentView = src_path('Views/pages/' . $view . '.php');
        $layout = $data['layout'] ?? 'main';
        unset($data['layout']);
        extract($data, EXTR_SKIP);
        require src_path('Views/layouts/' . $layout . '.php');
    }

    protected function redirect(string $path): never
    {
        redirect($path);
    }

    protected function flash(string $type, string $message): void
    {
        Flash::set($type, $message);
    }

    protected function json(array $payload, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_THROW_ON_ERROR);
        exit;
    }
}
