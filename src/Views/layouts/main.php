<?php

use MetaMyKad\Core\Flash;

$pageTitle = $pageTitle ?? 'MetaMyKad';
$flash = Flash::get();
$currentPath = current_path();
$currentBadge = $currentBadge ?? 'Pendaftar';
?>
<?php require src_path('Views/partials/head.php'); ?>
<div class="app-container">
    <aside class="sidebar">
        <div class="sidebar-panel">
            <div class="logo">
                <span class="logo-mark">
                    <img src="<?= e(asset('images/favicon.png')) ?>" alt="<?= e((string) config('app.name', 'MetaMyKad')) ?> logo">
                </span>
                <div class="logo-copy">
                    <span class="logo-wordmark"><?= e((string) config('app.name', 'MetaMyKad')) ?></span>
                    <span class="logo-subtitle">BITP3353 MULTIMEDIA DATABASE</span>
                </div>
            </div>
            <div class="sidebar-section">
                <p class="sidebar-kicker">Navigation</p>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/' ? 'is-active' : '' ?>" href="<?= e(url('/')) ?>">
                            <span class="nav-icon"><img src="<?= e(asset('images/nav/home.png')) ?>" alt="" aria-hidden="true"></span><span class="nav-text">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/dashboard' ? 'is-active' : '' ?>" href="<?= e(url('/dashboard')) ?>">
                            <span class="nav-icon"><img src="<?= e(asset('images/nav/dashboard.png')) ?>" alt="" aria-hidden="true"></span><span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/register' ? 'is-active' : '' ?>" href="<?= e(url('/register')) ?>">
                            <span class="nav-icon"><img src="<?= e(asset('images/nav/register.png')) ?>" alt="" aria-hidden="true"></span><span class="nav-text">Registration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/re-register' ? 'is-active' : '' ?>" href="<?= e(url('/re-register')) ?>">
                            <span class="nav-icon"><img src="<?= e(asset('images/nav/reregister.png')) ?>" alt="" aria-hidden="true"></span><span class="nav-text">Re-Registration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/search-attribute' ? 'is-active' : '' ?>" href="<?= e(url('/search-attribute')) ?>">
                            <span class="nav-icon"><img src="<?= e(asset('images/nav/abr.png')) ?>" alt="" aria-hidden="true"></span><span class="nav-text">ABR Search</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/search-text' ? 'is-active' : '' ?>" href="<?= e(url('/search-text')) ?>">
                            <span class="nav-icon"><img src="<?= e(asset('images/nav/tbr.png')) ?>" alt="" aria-hidden="true"></span><span class="nav-text">TBR Search</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/search-content' ? 'is-active' : '' ?>" href="<?= e(url('/search-content')) ?>">
                            <span class="nav-icon"><img src="<?= e(asset('images/nav/cbr.png')) ?>" alt="" aria-hidden="true"></span><span class="nav-text">CBR Search</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/history' ? 'is-active' : '' ?>" href="<?= e(url('/history')) ?>">
                            <span class="nav-icon"><img src="<?= e(asset('images/nav/history.png')) ?>" alt="" aria-hidden="true"></span><span class="nav-text">History</span>
                        </a>
                    </li>
                    <?php if (\MetaMyKad\Core\Auth::check()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/student-detail' ? 'is-active' : '' ?>"
                           href="<?= e(url('/student-detail?id=' . \MetaMyKad\Core\Auth::user()['id'])) ?>">
                            <span class="nav-icon"><img src="<?= e(asset('images/nav/profile.png')) ?>" alt="" aria-hidden="true"></span>
                            <span class="nav-text"><?= e(\MetaMyKad\Core\Auth::user()['full_name']) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="<?= e(url('/logout')) ?>" method="post" style="margin:0;">
                            <?php require src_path('Views/partials/csrf.php'); ?>
                            <button type="submit" class="nav-link" style="background:none;border:none;width:100%;text-align:left;cursor:pointer;color:var(--color-danger-text,#ff6b6b);">
                                <span class="nav-icon"><img src="<?= e(asset('images/nav/signout.png')) ?>" alt="" aria-hidden="true"></span><span class="nav-text">Sign Out</span>
                            </button>
                        </form>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/login' ? 'is-active' : '' ?>" href="<?= e(url('/login')) ?>">
                            <span class="nav-icon"><img src="<?= e(asset('images/nav/profile.png')) ?>" alt="" aria-hidden="true"></span><span class="nav-text">Student Login</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="sidebar-note">
                <span class="sidebar-note__label">Stack</span>
                <strong>Pure PHP + MySQL</strong>
                <p>Identity-first search, registration, and multimedia metadata in one custom console.</p>
            </div>
        </div>
    </aside>

    <div class="main-content">
        <header class="site-header">
            <div class="header-copy">
                <p class="eyebrow">BITP3353 Multimedia Database</p>
                <h1 class="page-title"><?= e($pageTitle) ?></h1>
            </div>
        </header>
        <main class="content">
            <?php require src_path('Views/partials/toast.php'); ?>
            <?php require $contentView; ?>
        </main>
        <footer class="status-footer">
            <div class="status-footer__group">
                <span>SYSTEM STATUS: <span class="status-online">OPERATIONAL</span></span>
                <span>STACK: PURE PHP + MYSQL</span>
            </div>
            <div>METAMYKAD | BITP3353 MULTIMEDIA DATABASE</div>
        </footer>
    </div>
</div>
<?php require src_path('Views/partials/confirm-dialog.php'); ?>
<div class="page-spinner" id="page-spinner" aria-hidden="true" role="status" aria-label="Loading">
    <div class="page-spinner__ring"></div>
</div>
<script src="<?= e(asset('js/fetch.js')) ?>"></script>
<script src="<?= e(asset('js/validate.js')) ?>"></script>
<script src="<?= e(asset('js/file-input.js')) ?>"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
<script src="<?= e(asset('js/player.js')) ?>"></script>
<script src="<?= e(asset('js/select.js')) ?>"></script>
</body>
</html>
