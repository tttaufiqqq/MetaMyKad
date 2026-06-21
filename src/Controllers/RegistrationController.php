<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use InvalidArgumentException;
use MetaMyKad\Core\Auth;
use MetaMyKad\Core\Session;
use MetaMyKad\Core\Validator;
use MetaMyKad\Models\CbrMetadata;
use MetaMyKad\Models\FileMetadata;
use MetaMyKad\Models\Student;
use MetaMyKad\Services\AutoTagger;
use MetaMyKad\Services\MetadataExtractor;
use MetaMyKad\Services\UploadService;

final class RegistrationController extends BaseController
{
    public function store(): void
    {
        $_SESSION['_old'] = $_POST;
        $mode             = ($_POST['mode'] ?? 'create') === 'update' ? 'update' : 'create';
        $isStubCompletion = false;
        $existingByMatric = null;
        $central          = null;

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
            ]);
        }

        if ($errors !== []) {
            $msgs = array_merge(...array_values($errors));
            $this->flash('error', implode(' ', $msgs));
            $this->redirect($mode === 'update' ? '/re-register' : '/register');
        }

        $student = new Student();

        // --- 2. For new registrations: stub completion check + open registration ---
        if ($mode === 'create') {
            $matric = trim((string) ($_POST['matric_number'] ?? ''));

            // Stub completion: logged-in user whose local account has no ic_number yet.
            // The stub was auto-created on their first login via mmdb2026.vstu.
            // We bypass the duplicate-matric guard and the SP for this path.
            $existingByMatric = $student->findByMatric($matric);
            if ($existingByMatric !== false) {
                if (Auth::check()
                    && (int) Auth::user()['id'] === (int) $existingByMatric['id']
                    && $existingByMatric['ic_number'] === null) {
                    $isStubCompletion = true;
                } else {
                    $this->flash('error', 'You already have a profile. Please log in.');
                    $this->redirect('/login');
                }
            }

            // Central check: sources password for enrolled students.
            // Not found in central = open registration (allowed — student sets own password).
            if (!$isStubCompletion) {
                try {
                    $central = $student->findInCentral($matric);
                    if ($central === false) {
                        $central = null; // allow open registration
                    }
                } catch (\Throwable) {
                    $central = null;
                }
            }
        }

        // --- 3. IC parse ---
        // Derive from the raw IC first (needs plain digits), then hash for storage.
        $derived = null;
        $icHash  = null;
        if ($icProvided) {
            try {
                $derived = $student->deriveFromIc((string) $_POST['ic_number']);
            } catch (InvalidArgumentException $e) {
                $this->flash('error', $e->getMessage());
                $this->redirect($mode === 'update' ? '/re-register' : '/register');
            }
            $icHash = hash('sha256', preg_replace('/\D+/', '', (string) $_POST['ic_number']));
        }
        $emailCategory = $student->classifyEmail((string) $_POST['email']);

        // --- 4. Detect existing student (by IC for re-registration logic) ---
        $existing = $icProvided ? $student->findByIc((string) $icHash) : false;

        if ($mode === 'update' && !Auth::check()) {
            $this->flash('error', 'You must be logged in to re-register.');
            $this->redirect('/login');
        }

        if ($mode === 'update' && $existing === false) {
            $this->flash('error', 'IC number not found. Please register first.');
            $this->redirect('/re-register');
        }

        if ($mode === 'update' && $existing !== false && (int) Auth::user()['id'] !== (int) $existing['id']) {
            http_response_code(403);
            require src_path('Views/errors/403.php');
            exit;
        }

        // --- 4. Re-registration: delete only files whose type is being replaced ---
        if ($existing !== false) {
            $fileModel = new FileMetadata();
            foreach (['photo', 'audio', 'pdf', 'video'] as $type) {
                $entry = $_FILES[$type] ?? null;
                if ($entry === null || ($entry['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                    continue; // no new upload for this type — keep the existing file
                }
                $oldFile = $fileModel->findByStudentIdAndType((int) $existing['id'], $type);
                if ($oldFile === false) {
                    continue;
                }
                // Remove DB record first (CASCADE handles cbr_metadata + file_tags)
                try {
                    (new FileMetadata())->callProcedure('sp_delete_file', [$oldFile['id']]);
                } catch (\Throwable $e) {
                    $this->flash('error', "Could not remove existing {$type} record. Registration aborted.");
                    $this->redirect('/re-register');
                }
                // Remove physical file
                $absPath = base_path($oldFile['file_path']);
                if (file_exists($absPath) && !unlink($absPath)) {
                    $this->flash('error', "Could not remove existing file '{$oldFile['filename']}'. Registration aborted.");
                    $this->redirect('/re-register');
                }
            }
        }

        // --- 5. Create/update student record ---
        if ($isStubCompletion) {
            // Stub completion: direct UPDATE + history INSERT.
            // Cannot use sp_register_student — it would attempt INSERT and hit a
            // DUPLICATE KEY on matric_number (the stub row already exists).
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
            \MetaMyKad\Core\Database::connection()->prepare(
                'INSERT INTO registration_history (ic_number, full_name, files_uploaded, badge_at_time, action)
                 VALUES (:ic, :name, 0, \'Pendaftar\', \'new\')'
            )->execute(['ic' => $icHash ?? '', 'name' => (string) ($_POST['full_name'] ?? '')]);
            $studentId = $stubId;
        } else {
            // Normal new registration or re-registration via stored procedure.
            // For open registrations (no central match), password comes from the form.
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

        $fileModel   = new FileMetadata();
        $autoTagger  = new AutoTagger();
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

            $autoTagger->tag($fileId, $fileType);
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

        // Auto-login on new registration so the student can edit their profile immediately.
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
