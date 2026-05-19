<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Models\FileMetadata;

final class FileController extends BaseController
{
    public function serve(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            http_response_code(404);
            exit;
        }

        $fileModel = new FileMetadata();
        $file      = $fileModel->find($id);

        if ($file === false) {
            http_response_code(404);
            exit;
        }

        $absPath = base_path((string) $file['file_path']);

        if (!file_exists($absPath) || !is_readable($absPath)) {
            http_response_code(404);
            exit;
        }

        $mime = (string) $file['mime_type'];
        $size = filesize($absPath);

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $size);
        header('Content-Disposition: inline; filename="' . addslashes((string) $file['filename']) . '"');
        header('Cache-Control: private, max-age=3600');
        header('Accept-Ranges: bytes');

        readfile($absPath);
        exit;
    }
}
