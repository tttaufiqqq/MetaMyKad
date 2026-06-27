<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Core\Auth;
use MetaMyKad\Core\Session;
use MetaMyKad\Core\Validator;
use MetaMyKad\Models\CbrMetadata;
use MetaMyKad\Models\FileMetadata;
use MetaMyKad\Models\Student;
use MetaMyKad\Services\AutoTagger;
use MetaMyKad\Services\MetadataExtractor;
use MetaMyKad\Services\UploadService;

trait StudentControllerWriteTrait
{
    public function deleteAccount(): void
    {
        $loggedUser = Auth::user();
        $studentId  = (int) ($_POST['student_id'] ?? 0);

        if ($studentId < 1 || (int) $loggedUser['id'] !== $studentId) {
            http_response_code(403);
            require src_path('Views/errors/403.php');
            exit;
        }

        $studentModel = new Student();
        $student      = $studentModel->find($studentId);

        if ($student === false) {
            $this->flash('error', 'Student record not found.');
            $this->redirect('/dashboard');
        }

        $fileModel = new FileMetadata();
        $files     = $fileModel->findByStudentId($studentId);
        foreach ($files as $file) {
            $absPath = base_path((string) $file['file_path']);
            if (file_exists($absPath)) {
                unlink($absPath);
            }
        }

        $studentModel->delete($studentId);
        Session::destroy();

        $this->flash('success', 'Your account has been deleted.');
        $this->redirect('/');
    }

    public function update(): void
    {
        $loggedUser = Auth::user();
        $studentId  = (int) ($_POST['student_id'] ?? 0);

        if ($studentId < 1 || (int) $loggedUser['id'] !== $studentId) {
            http_response_code(403);
            require src_path('Views/errors/403.php');
            exit;
        }

        $errors = Validator::validate($_POST, [
            'full_name' => ['required'],
            'phone'     => ['required', 'phone'],
            'email'     => ['required', 'email'],
        ]);

        if ($errors !== []) {
            $msgs = array_merge(...array_values($errors));
            $this->flash('error', implode(' ', $msgs));
            $this->redirect('/student-detail?id=' . $studentId);
        }

        $studentModel  = new Student();
        $emailCategory = $studentModel->classifyEmail((string) $_POST['email']);

        $studentModel->updateProfile($studentId, [
            'full_name'      => trim((string) $_POST['full_name']),
            'phone'          => trim((string) $_POST['phone']),
            'email'          => trim((string) $_POST['email']),
            'email_category' => $emailCategory,
        ]);

        $newPassword     = (string) ($_POST['new_password'] ?? '');
        $currentPassword = (string) ($_POST['current_password'] ?? '');

        if ($newPassword !== '') {
            $stored = $studentModel->find($studentId);
            if ($stored === false || !password_verify($currentPassword, (string) $stored['password'])) {
                $this->flash('error', 'Current password is incorrect. Profile info was saved, but password was not changed.');
                $this->redirect('/student-detail?id=' . $studentId);
            }
            $studentModel->updatePassword($studentId, password_hash($newPassword, PASSWORD_DEFAULT));
        }

        $fileModel     = new FileMetadata();
        $uploadService = new UploadService();
        $extractor     = new MetadataExtractor();
        $autoTagger    = new AutoTagger();

        try {
            $uploads = $uploadService->processAll($studentId, $_FILES);
        } catch (\RuntimeException $e) {
            $this->flash('error', 'File upload error: ' . $e->getMessage());
            $this->redirect('/student-detail?id=' . $studentId);
        }

        foreach ($uploads as $fileType => $uploadData) {
            $oldFile = $fileModel->findByStudentIdAndType($studentId, $fileType);
            if ($oldFile !== false) {
                $absPath = base_path((string) $oldFile['file_path']);
                if (file_exists($absPath)) {
                    unlink($absPath);
                }
                try {
                    $fileModel->callProcedure('sp_delete_file', [(int) $oldFile['id']]);
                } catch (\Throwable) {
                    $fileModel->delete((int) $oldFile['id']);
                }
            }

            $rawDate      = trim((string) ($_POST['original_date_' . $fileType] ?? ''));
            $originalDate = preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawDate) ? $rawDate : null;

            $fileId = $fileModel->insert([
                'student_id'      => $studentId,
                'file_type'       => $fileType,
                'filename'        => $uploadData['filename'],
                'stored_filename' => $uploadData['stored_filename'],
                'file_path'       => $uploadData['file_path'],
                'file_size'       => $uploadData['file_size'],
                'mime_type'       => $uploadData['mime_type'],
                'original_date'   => $originalDate,
            ]);

            try {
                $extractor->extract($fileId, $fileType, base_path($uploadData['file_path']));
            } catch (\Throwable $e) {
                error_log("Metadata extraction failed for file {$fileId}: " . $e->getMessage());
            }

            if (in_array($fileType, ['audio', 'video'], true)) {
                $browserDuration = (int) ($_POST['duration_sec_' . $fileType] ?? 0);
                if ($browserDuration > 0) {
                    (new CbrMetadata())->updateDuration($fileId, $fileType, $browserDuration);
                }
            }

            $autoTagger->tag($fileId, $fileType);
        }

        (new Student())->callProcedure('sp_update_badge', [$studentId]);

        $user = Auth::user();
        Session::put('user', array_merge($user, [
            'full_name' => trim((string) $_POST['full_name']),
        ]));

        $this->flash('success', 'Profile updated successfully.');
        $this->redirect('/student-detail?id=' . $studentId);
    }
}
