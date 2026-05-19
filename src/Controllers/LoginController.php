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

        $this->render('login', ['pageTitle' => 'Student Login']);
    }

    public function store(): void
    {
        $matric   = trim((string) ($_POST['matric_number'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $student = (new Student())->findByMatric($matric);

        if ($student === false || !password_verify($password, (string) $student['password'])) {
            $this->flash('error', 'Invalid matric number or password.');
            $this->redirect('/login');
        }

        Session::put('user', [
            'id'             => (int) $student['id'],
            'full_name'      => $student['full_name'],
            'matric_number'  => $student['matric_number'],
        ]);

        $this->redirect('/student-detail?id=' . $student['id']);
    }

    public function logout(): void
    {
        Session::destroy();
        $this->flash('success', 'You have been signed out.');
        $this->redirect('/');
    }
}
