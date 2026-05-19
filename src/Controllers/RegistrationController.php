<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Core\Validator;
use MetaMyKad\Models\Student;

final class RegistrationController extends BaseController
{
    public function store(): void
    {
        $_SESSION['_old'] = $_POST;
        $mode = ($_POST['mode'] ?? 'create') === 'update' ? 'update' : 'create';

        $errors = Validator::validate($_POST, [
            'ic_number' => ['required', 'ic'],
            'full_name' => ['required'],
            'phone' => ['required'],
            'email' => ['required', 'email'],
        ]);

        if ($errors !== []) {
            $this->flash('error', 'Please fix the registration form errors first.');
            $this->redirect('/register');
        }

        $student = new Student();
        $derived = $student->deriveFromIc((string) $_POST['ic_number']);
        $emailCategory = $student->classifyEmail((string) $_POST['email']);

        unset($_SESSION['_old']);

        $this->flash(
            'success',
            sprintf(
                '%s scaffold received. Derived state: %s. Email category: %s.',
                ucfirst($mode),
                $derived['state_of_birth'],
                $emailCategory
            )
        );

        $this->redirect($mode === 'update' ? '/re-register' : '/register');
    }

    public function deleteFile(): void
    {
        $this->flash('warning', 'Delete route scaffolded. Implement DB delete and cleanup next.');
        $this->redirect('/student-detail');
    }
}
