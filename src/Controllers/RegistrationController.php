<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use InvalidArgumentException;
use MetaMyKad\Core\Validator;
use MetaMyKad\Models\CbrMetadata;
use MetaMyKad\Models\FileMetadata;
use MetaMyKad\Models\Student;
use MetaMyKad\Services\MetadataExtractor;
use MetaMyKad\Services\UploadService;

final class RegistrationController extends BaseController
{
    public function store(): void
    {
        $_SESSION['_old'] = $_POST;
        $mode = ($_POST['mode'] ?? 'create') === 'update' ? 'update' : 'create';

        // --- 1. Base validation ---
        $icProvided       = trim((string) ($_POST['ic_number'] ?? '')) !== '';
        $passportProvided = trim((string) ($_POST['passport_number'] ?? '')) !== '';

        $idRules = [];
        if ($icProvided || !$passportProvided) {
            $idRules['ic_number'] = ['required', 'ic'];
        }
        if ($passportProvided && !$icProvided) {
            $idRules['passport_number'] = ['required', 'passport'];
        }

        $errors = Validator::validate($_POST, array_merge($idRules, [
            'full_name' => ['required'],
            'phone'     => ['required', 'phone'],
            'email'     => ['required', 'email'],
        ]));

        if ($mode === 'create') {
            $errors += Validator::validate($_POST, [
                'matric_number' => ['required'],
                'password'      => ['required'],
            ]);
        }

        if ($errors !== []) {
            $msgs = array_merge(...array_values($errors));
            $this->flash('error', implode(' ', $msgs));
            $this->redirect($mode === 'update' ? '/re-register' : '/register');
        }

        // --- 2. IC parse ---
        $student = new Student();
        $derived = null;
        if ($icProvided) {
            try {
                $derived = $student->deriveFromIc((string) $_POST['ic_number']);
            } catch (InvalidArgumentException $e) {
                $this->flash('error', $e->getMessage());
                $this->redirect($mode === 'update' ? '/re-register' : '/register');
            }
        }
        $emailCategory = $student->classifyEmail((string) $_POST['email']);

        // --- 3. Detect existing student ---
        $existing = $icProvided ? $student->findByIc((string) $_POST['ic_number']) : false;

        if ($mode === 'update' && $existing === false) {
            $this->flash('error', 'IC number not found. Please register first.');
            $this->redirect('/re-register');
        }

        // --- 4. Re-registration: unlink old physical files ---
        if ($existing !== false) {
            $fileModel = new FileMetadata();
            $oldFiles  = $fileModel->findByStudentId((int) $existing['id']);
            foreach ($oldFiles as $oldFile) {
                $absPath = base_path($oldFile['file_path']);
                if (file_exists($absPath) && !unlink($absPath)) {
                    $this->flash('error', "Could not remove existing file '{$oldFile['filename']}'. Registration aborted.");
                    $this->redirect('/re-register');
                }
            }
        }

        // --- 5. Call sp_register_student → get student_id ---
        $passwordHash = $existing !== false
            ? ($existing['password'] ?? '')
            : password_hash((string) $_POST['password'], PASSWORD_DEFAULT);

        $result = (new Student())->callProcedure('sp_register_student', [
            $_POST['ic_number'] ?? '',
            $existing !== false ? null : $_POST['matric_number'],
            $existing !== false ? null : $passwordHash,
            $_POST['full_name'],
            $_POST['phone'],
            $_POST['email'],
            $emailCategory,
            $derived['date_of_birth'] ?? null,
            $derived['gender'] ?? null,
            $derived['state_of_birth'] ?? null,
            $derived['age'] ?? null,
        ]);

        $studentId = (int) ($result[0]['student_id'] ?? 0);
        if ($studentId === 0) {
            $this->flash('error', 'Registration failed. Please try again.');
            $this->redirect($mode === 'update' ? '/re-register' : '/register');
        }

        // --- 6-8. Upload, insert file_metadata, extract metadata ---
        $uploadService = new UploadService();
        $extractor     = new MetadataExtractor();
        $movedFiles    = [];

        try {
            $uploads = $uploadService->processAll($studentId, $_FILES, (string) $_POST['full_name']);
        } catch (\RuntimeException $e) {
            $this->flash('error', 'File upload error: ' . $e->getMessage());
            $this->redirect($mode === 'update' ? '/re-register' : '/register');
        }

        $fileModel = new FileMetadata();
        foreach ($uploads as $fileType => $uploadData) {
            $movedFiles[] = base_path($uploadData['file_path']);

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
        }

        // --- 9. Recompute badge ---
        (new Student())->callProcedure('sp_update_badge', [$studentId]);

        // --- 10. Backfill history row with actual file count + badge ---
        // The history row was written before uploads, so files_uploaded was 0.
        $db = \MetaMyKad\Core\Database::connection();
        $db->prepare(
            'UPDATE registration_history rh
             JOIN (SELECT MAX(id) AS mid FROM registration_history WHERE ic_number = :ic) t
               ON rh.id = t.mid
             JOIN students s ON s.id = :sid
             SET rh.files_uploaded = (SELECT COUNT(*) FROM file_metadata WHERE student_id = :sid2),
                 rh.badge_at_time  = s.badge'
        )->execute([
            'ic'   => $_POST['ic_number'] ?? '',
            'sid'  => $studentId,
            'sid2' => $studentId,
        ]);

        unset($_SESSION['_old']);
        $this->flash('success', $existing !== false ? 'Re-registration successful.' : 'Registration successful.');
        $this->redirect('/student-detail?id=' . $studentId);
    }

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

        $studentId = (int) $file['student_id'];
        $absPath   = base_path($file['file_path']);

        // DB delete via procedure (CASCADE handles cbr_metadata + file_tags)
        try {
            (new FileMetadata())->callProcedure('sp_delete_file', [$fileId]);
        } catch (\Throwable $e) {
            $this->flash('error', 'Database error — file was not deleted.');
            $this->redirect('/student-detail?id=' . $studentId);
        }

        // Filesystem delete (after DB commit)
        if (file_exists($absPath) && !unlink($absPath)) {
            error_log("File delete failed, manual cleanup needed: {$absPath}");
            $this->flash('warning', 'File record removed, but the physical file could not be deleted. Manual cleanup may be needed.');
        } else {
            $this->flash('success', 'File deleted successfully.');
        }

        $this->redirect('/student-detail?id=' . $studentId);
    }
}
