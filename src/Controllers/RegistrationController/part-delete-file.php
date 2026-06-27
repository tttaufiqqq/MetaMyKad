<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Core\Auth;
use MetaMyKad\Models\FileMetadata;

trait RegistrationDeleteFileTrait
{
    public function deleteFile(): void
    {
        $fileId = (int) ($_POST['file_id'] ?? 0);

        if ($fileId < 1) {
            $this->flash('error', 'Invalid file reference.');
            $this->redirect('/dashboard');
        }

        $fileModel = new FileMetadata();
        $file      = $fileModel->find($fileId);

        if ($file === false) {
            $this->flash('error', 'File not found.');
            $this->redirect('/dashboard');
        }

        if ((int) Auth::user()['id'] !== (int) $file['student_id']) {
            http_response_code(403);
            require src_path('Views/errors/403.php');
            exit;
        }

        $studentId = (int) $file['student_id'];
        $absPath   = base_path($file['file_path']);

        try {
            (new FileMetadata())->callProcedure('sp_delete_file', [$fileId]);
        } catch (\Throwable $e) {
            $this->flash('error', 'Database error — file was not deleted.');
            $this->redirect('/student-detail?id=' . $studentId);
        }

        if (file_exists($absPath) && !unlink($absPath)) {
            error_log("File delete failed, manual cleanup needed: {$absPath}");
            $this->flash('warning', 'File record removed, but the physical file could not be deleted. Manual cleanup may be needed.');
        } else {
            $this->flash('success', 'File deleted successfully.');
        }

        $this->redirect('/student-detail?id=' . $studentId);
    }
}
