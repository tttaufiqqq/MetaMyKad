<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

final class PageController extends BaseController
{
    public function home(): void
    {
        $this->render('home', ['pageTitle' => 'Home', 'layout' => 'home']);
    }

    public function register(): void
    {
        $matric           = trim((string) ($_GET['matric'] ?? ''));
        $prefill          = [];
        $isStubCompletion = false;

        // If the logged-in user has a stub account (ic_number IS NULL), pre-fill from
        // their existing row and switch to stub-completion mode (IC required, no passport).
        if (\MetaMyKad\Core\Auth::check()) {
            $loggedIn = \MetaMyKad\Core\Auth::user();
            $existing = (new \MetaMyKad\Models\Student())->find((int) $loggedIn['id']);
            if ($existing !== false && $existing['ic_number'] === null) {
                $isStubCompletion = true;
                $prefill = [
                    'matric'    => $existing['matric_number'],
                    'full_name' => $existing['full_name'],
                    'phone'     => $existing['phone'] ?? '',
                ];
            }
        }

        // Prefill from central if ?matric= provided and not already doing stub completion
        if (!$isStubCompletion && $matric !== '') {
            try {
                $central = (new \MetaMyKad\Models\Student())->findInCentral($matric);
                if ($central !== false) {
                    $prefill = [
                        'matric'    => $central['matric_no'],
                        'full_name' => $central['full_name'],
                        'phone'     => $central['phone_no'],
                    ];
                }
            } catch (\Throwable) {
                // central unavailable — no prefill
            }
        }

        $this->render('register', [
            'pageTitle'        => $isStubCompletion ? 'Complete Your Profile' : 'Registration',
            'prefill'          => $prefill,
            'isStubCompletion' => $isStubCompletion,
        ]);
    }

    public function reRegister(): void
    {
        $this->render('re-register', ['pageTitle' => 'Re-Registration']);
    }

    public function dashboard(): void
    {
        $this->render('dashboard', ['pageTitle' => 'Dashboard']);
    }

    public function studentDetail(): void
    {
        $this->render('student-detail', ['pageTitle' => 'Student Detail']);
    }

    public function searchAttribute(): void
    {
        $this->render('search-attribute', ['pageTitle' => 'Attribute Search']);
    }

    public function searchText(): void
    {
        $this->render('search-text', ['pageTitle' => 'Text Search']);
    }

    public function searchContent(): void
    {
        $this->render('search-content', ['pageTitle' => 'Content Search']);
    }

    public function history(): void
    {
        $this->render('history', ['pageTitle' => 'History']);
    }
}
