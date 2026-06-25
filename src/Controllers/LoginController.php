<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Core\Auth;
use MetaMyKad\Core\Session;
use MetaMyKad\Models\Student;

final class LoginController extends BaseController
{
    public function showForm(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->redirect('/student-detail?id=' . $user['id']);
        }

        $this->render('login', [
            'pageTitle'     => 'Student Login',
            'prefillMatric' => trim((string) ($_GET['matric'] ?? '')),
        ]);
    }

    public function store(): void
    {
        $matric        = trim((string) ($_POST['matric_number'] ?? ''));
        $password      = (string) ($_POST['password'] ?? '');
        $postRedirect  = trim((string) ($_POST['redirect'] ?? ''));

        $studentModel = new Student();
        $student      = $studentModel->findByMatric($matric);

        // No local account — try central DB fallback to auto-create a stub row
        if ($student === false) {
            $central = false;
            try {
                $central = $studentModel->findInCentral($matric);
            } catch (\Throwable) {
                // central DB unreachable — fall through to invalid-credentials error
            }

            if ($central !== false && $central['password'] !== null &&
                (string) $central['password'] === hash('sha256', $password)) {
                $stubId = $studentModel->createStub($central);
                Session::put('user', [
                    'id'            => $stubId,
                    'full_name'     => $central['full_name'],
                    'matric_number' => $central['matric_no'],
                ]);
                $this->flash('warning', 'Welcome! Your account has been created from the student system. Please complete your profile by adding your IC number and uploading your multimedia files.');
                $this->redirectAfterLogin($stubId, $postRedirect);
            }

            $this->flash('error', 'Invalid matric number or password.');
            $this->redirect('/login');
        }

        // Local account exists — verify password
        if ($student['password'] === null ||
            (string) $student['password'] !== hash('sha256', $password)) {
            $this->flash('error', 'Invalid matric number or password.');
            $this->redirect('/login');
        }

        Session::put('user', [
            'id'            => (int) $student['id'],
            'full_name'     => $student['full_name'],
            'matric_number' => $student['matric_number'],
        ]);

        $this->redirectAfterLogin((int) $student['id'], $postRedirect);
    }

    /**
     * Redirect to $postRedirect if it is a safe relative path, otherwise fall back
     * to the student's own detail page.
     */
    private function redirectAfterLogin(int $userId, string $postRedirect): never
    {
        if ($postRedirect !== ''
            && str_starts_with($postRedirect, '/')
            && !str_starts_with($postRedirect, '//')) {
            $this->redirect($postRedirect);
        }
        $this->redirect('/student-detail?id=' . $userId);
    }

    public function logout(): void
    {
        Session::destroy();
        $this->flash('success', 'You have been signed out.');
        $this->redirect('/');
    }
}
