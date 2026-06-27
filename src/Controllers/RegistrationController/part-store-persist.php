<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Core\Auth;
use MetaMyKad\Core\Database;
use MetaMyKad\Core\Session;
use MetaMyKad\Models\CbrMetadata;
use MetaMyKad\Models\FileMetadata;
use MetaMyKad\Models\Student;
use MetaMyKad\Services\AutoTagger;
use MetaMyKad\Services\MetadataExtractor;
use MetaMyKad\Services\UploadService;

trait RegistrationStorePersistTrait
{
    private function persistStudentRecord(
        string $mode,
        bool $isStubCompletion,
        array|null $existingByMatric,
        array|null $central,
        ?array $derived,
        ?string $icHash,
        string $emailCategory,
        array|false $existing,
    ): int {
        if ($isStubCompletion) {
            $stubId = (int) $existingByMatric['id'];
            (new Student())->completeStub($stubId, [
                'ic_number'      => $icHash,
                'full_name'      => (string) ($_POST['full_name'] ?? ''),
                'phone'          => (string) ($_POST['phone'] ?? ''),
                'email'          => (string) ($_POST['email'] ?? ''),
                'email_category' => $emailCategory,
                'date_of_birth'  => $derived['date_of_birth'] ?? null,
                'gender'         => $derived['gender'] ?? null,
                'state_of_birth' => $derived['state_of_birth'] ?? null,
                'age'            => $derived['age'] ?? null,
            ]);
            Database::connection()->prepare(
                'INSERT INTO registration_history (ic_number, full_name, files_uploaded, badge_at_time, action)
                 VALUES (:ic, :name, 0, \'Pendaftar\', \'new\')'
            )->execute(['ic' => $icHash ?? '', 'name' => (string) ($_POST['full_name'] ?? '')]);
            return $stubId;
        }

        if ($mode === 'create' && $central === null &&
            trim((string) ($_POST['password'] ?? '')) === '') {
            $this->flash('error', 'Please set a password for your account.');
            $this->redirect('/register');
        }

        $passwordHash = $existing !== false
            ? ($existing['password'] ?? null)
            : ($central !== null
                ? $central['password']
                : hash('sha256', (string) ($_POST['password'] ?? '')));

        $result = (new Student())->callProcedure('sp_register_student', [
            $icHash ?? '',
            $existing !== false ? null : $_POST['matric_number'],
            $passwordHash,
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
        return $studentId;
    }

    private function processUploadsAndMetadata(int $studentId, string $mode): void
    {
        $uploadService = new UploadService();
        $extractor     = new MetadataExtractor();

        try {
            $uploads = $uploadService->processAll($studentId, $_FILES, (string) $_POST['full_name']);
        } catch (\RuntimeException $e) {
            $this->flash('error', 'File upload error: ' . $e->getMessage());
            $this->redirect($mode === 'update' ? '/re-register' : '/register');
        }

        $fileModel  = new FileMetadata();
        $autoTagger = new AutoTagger();
        foreach ($uploads as $fileType => $uploadData) {
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
    }

    private function finalizeAndRedirect(int $studentId, array|false $existing, bool $isStubCompletion, string $mode): void
    {
        (new Student())->callProcedure('sp_update_badge', [$studentId]);

        Database::connection()->prepare(
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

        if ($mode === 'create') {
            $registered = (new Student())->findByMatric((string) ($_POST['matric_number'] ?? ''));
            if ($registered !== false) {
                Session::put('user', [
                    'id'            => (int) $registered['id'],
                    'full_name'     => $registered['full_name'],
                    'matric_number' => $registered['matric_number'],
                ]);
            }
        }

        $successMsg = $existing !== false
            ? 'Re-registration successful.'
            : ($isStubCompletion ? 'Profile completed! Welcome to MetaMyKad.' : 'Registration successful. Welcome to MetaMyKad!');
        $this->flash('success', $successMsg);
        $this->redirect('/student-detail?id=' . $studentId);
    }
}
