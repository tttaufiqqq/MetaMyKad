<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use InvalidArgumentException;
use MetaMyKad\Core\Auth;
use MetaMyKad\Core\Validator;
use MetaMyKad\Models\FileMetadata;
use MetaMyKad\Models\Student;

trait RegistrationStoreTrait
{
    public function store(): void
    {
        $_SESSION['_old'] = $_POST;
        $mode             = ($_POST['mode'] ?? 'create') === 'update' ? 'update' : 'create';
        $isStubCompletion = false;
        $existingByMatric = null;
        $central          = null;

        $this->validateStoreInput($mode);

        if ($mode === 'create') {
            [$isStubCompletion, $existingByMatric, $central] = $this->resolveNewRegistration();
        }

        [$derived, $icHash, $emailCategory] = $this->parseIcAndEmail($mode);
        $existing = $this->resolveExistingAndAuth($icHash, $mode);
        $this->cleanupReplacedFiles($existing);
        $studentId = $this->persistStudentRecord($mode, $isStubCompletion, $existingByMatric, $central, $derived, $icHash, $emailCategory, $existing);
        $this->processUploadsAndMetadata($studentId, $mode);
        $this->finalizeAndRedirect($studentId, $existing, $isStubCompletion, $mode);
    }

    private function validateStoreInput(string $mode): void
    {
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
            $errors += Validator::validate($_POST, ['matric_number' => ['required']]);
        }

        if ($errors !== []) {
            $msgs = array_merge(...array_values($errors));
            $this->flash('error', implode(' ', $msgs));
            $this->redirect($mode === 'update' ? '/re-register' : '/register');
        }
    }

    private function resolveNewRegistration(): array
    {
        $matric           = trim((string) ($_POST['matric_number'] ?? ''));
        $student          = new Student();
        $isStubCompletion = false;
        $existingByMatric = null;
        $central          = null;

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

        if (!$isStubCompletion) {
            try {
                $central = $student->findInCentral($matric);
                if ($central === false) {
                    $central = null;
                }
            } catch (\Throwable) {
                $central = null;
            }
            if ($central !== null && !Auth::check()) {
                $this->flash('error', 'Please sign in with your VSTU credentials before completing your profile.');
                $this->redirect('/register');
            }
        }

        return [$isStubCompletion, $existingByMatric, $central];
    }

    private function parseIcAndEmail(string $mode): array
    {
        $icProvided = trim((string) ($_POST['ic_number'] ?? '')) !== '';
        $student    = new Student();
        $derived    = null;
        $icHash     = null;

        if ($icProvided) {
            try {
                $derived = $student->deriveFromIc((string) $_POST['ic_number']);
            } catch (InvalidArgumentException $e) {
                $this->flash('error', $e->getMessage());
                $this->redirect($mode === 'update' ? '/re-register' : '/register');
            }
            $icHash = hash('sha256', preg_replace('/\D+/', '', (string) $_POST['ic_number']));
        }

        return [$derived, $icHash, $student->classifyEmail((string) $_POST['email'])];
    }

    private function resolveExistingAndAuth(?string $icHash, string $mode): array|false
    {
        $existing = $icHash !== null ? (new Student())->findByIc($icHash) : false;

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

        return $existing;
    }

    private function cleanupReplacedFiles(array|false $existing): void
    {
        if ($existing === false) {
            return;
        }
        $fileModel = new FileMetadata();
        foreach (['photo', 'audio', 'pdf', 'video'] as $type) {
            $entry = $_FILES[$type] ?? null;
            if ($entry === null || ($entry['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $oldFile = $fileModel->findByStudentIdAndType((int) $existing['id'], $type);
            if ($oldFile === false) {
                continue;
            }
            try {
                (new FileMetadata())->callProcedure('sp_delete_file', [$oldFile['id']]);
            } catch (\Throwable) {
                $this->flash('error', "Could not remove existing {$type} record. Registration aborted.");
                $this->redirect('/re-register');
            }
            $absPath = base_path($oldFile['file_path']);
            if (file_exists($absPath) && !unlink($absPath)) {
                $this->flash('error', "Could not remove existing file '{$oldFile['filename']}'. Registration aborted.");
                $this->redirect('/re-register');
            }
        }
    }
}
