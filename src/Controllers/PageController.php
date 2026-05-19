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
        $this->render('register', ['pageTitle' => 'Registration']);
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
