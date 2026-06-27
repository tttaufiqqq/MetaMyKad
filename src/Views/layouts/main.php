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
                    <span class="logo-subtitle">BITP3353 &middot; GROUP GS02</span>
                </div>
            </div>
            <div class="sidebar-section">
                <p class="sidebar-kicker">Navigation</p>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/' ? 'is-active' : '' ?>" href="<?= e(url('/')) ?>">
                            <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/><path d="M9 21V12h6v9"/></svg></span><span class="nav-text">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/dashboard' ? 'is-active' : '' ?>" href="<?= e(url('/dashboard')) ?>">
                            <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></span><span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/students' ? 'is-active' : '' ?>" href="<?= e(url('/students')) ?>">
                            <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="7" r="3"/><path d="M3 21v-2a5 5 0 0 1 5-5h2"/><circle cx="17" cy="11" r="3"/><path d="M14.5 21v-1.5a3.5 3.5 0 0 1 3.5-3.5h0a3.5 3.5 0 0 1 3.5 3.5V21"/></svg></span><span class="nav-text">Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/register' ? 'is-active' : '' ?>" href="<?= e(url('/register')) ?>">
                            <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg></span><span class="nav-text">Registration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/search-attribute' ? 'is-active' : '' ?>" href="<?= e(url('/search-attribute')) ?>">
                            <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/><line x1="11" y1="8" x2="11" y2="14"/></svg></span><span class="nav-text">ABR Search</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/search-text' ? 'is-active' : '' ?>" href="<?= e(url('/search-text')) ?>">
                            <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="9" x2="14" y2="9"/><line x1="8" y1="13" x2="12" y2="13"/></svg></span><span class="nav-text">TBR Search</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/search-content' ? 'is-active' : '' ?>" href="<?= e(url('/search-content')) ?>">
                            <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><rect x="7" y="8" width="3" height="3" rx="0.5"/><polyline points="11 14 13 12 15 14"/></svg></span><span class="nav-text">CBR Search</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/history' ? 'is-active' : '' ?>" href="<?= e(url('/history')) ?>">
                            <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/></svg></span><span class="nav-text">History</span>
                        </a>
                    </li>
                    <?php if (\MetaMyKad\Core\Auth::check()): ?>
                    <li class="nav-item nav-item--user">
                        <a class="nav-link <?= $currentPath === '/student-detail' ? 'is-active' : '' ?>"
                           href="<?= e(url('/student-detail?id=' . \MetaMyKad\Core\Auth::user()['id'])) ?>">
                            <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></span>
                            <span class="nav-text"><?= e(\MetaMyKad\Core\Auth::user()['full_name']) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="<?= e(url('/logout')) ?>" method="post" style="margin:0;">
                            <?php require src_path('Views/partials/csrf.php'); ?>
                            <button type="submit" class="nav-link nav-link--signout" style="background:none;border:none;width:100%;text-align:left;cursor:pointer;">
                                <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span><span class="nav-text">Sign Out</span>
                            </button>
                        </form>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/login' ? 'is-active' : '' ?>" href="<?= e(url('/login')) ?>">
                            <span class="nav-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></span><span class="nav-text">Student Login</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </aside>

    <div class="main-content">
        <header class="site-header">
            <div class="header-copy">
                <p class="eyebrow">BITP3353 Multimedia Database &nbsp;|&nbsp; GS02 Project</p>
                <h1 class="page-title"><?= e($pageTitle) ?></h1>
                <?php if ($currentPath !== '/'): ?>
                <button type="button" class="back-btn" id="global-back-btn"
                        data-fallback="<?= e(url('/')) ?>"
                        aria-label="Go back to previous page">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Back
                </button>
                <?php endif; ?>
            </div>
        </header>
        <main class="content">
            <?php require src_path('Views/partials/toast.php'); ?>
            <?php require $contentView; ?>
        </main>
        <footer class="status-footer">
            <div>METAMYKAD | BITP3353 MULTIMEDIA DATABASE | GROUP GS02</div>
        </footer>
    </div>
</div>
<?php require src_path('Views/partials/student-modal.php'); ?>
<?php require src_path('Views/partials/badge-guide-modal.php'); ?>
<?php require src_path('Views/partials/onboarding-modal.php'); ?>
<?php require src_path('Views/partials/confirm-dialog.php'); ?>
<div class="page-spinner" id="page-spinner" aria-hidden="true" role="status" aria-label="Loading">
    <div class="page-spinner__ring"></div>
</div>
<script>
(function () {
    var btn = document.getElementById('global-back-btn');
    if (!btn) return;
    btn.addEventListener('click', function () {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = btn.getAttribute('data-fallback');
        }
    });
}());
</script>
<script src="<?= e(asset('js/fetch.js')) ?>"></script>
<script src="<?= e(asset('js/validate.js')) ?>"></script>
<script src="<?= e(asset('js/file-input.js')) ?>"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
<script src="<?= e(asset('js/player.js')) ?>"></script>
<script src="<?= e(asset('js/select.js')) ?>"></script>
<script src="<?= e(asset('js/row-modal.js')) ?>"></script>
<script src="<?= e(asset('js/tags.js')) ?>"></script>
</body>
</html>
