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
                            <span class="nav-icon">🏠</span><span class="nav-text">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/dashboard' ? 'is-active' : '' ?>" href="<?= e(url('/dashboard')) ?>">
                            <span class="nav-icon">📊</span><span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/register' ? 'is-active' : '' ?>" href="<?= e(url('/register')) ?>">
                            <span class="nav-icon">📝</span><span class="nav-text">Registration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/re-register' ? 'is-active' : '' ?>" href="<?= e(url('/re-register')) ?>">
                            <span class="nav-icon">♻️</span><span class="nav-text">Re-Registration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/search-attribute' ? 'is-active' : '' ?>" href="<?= e(url('/search-attribute')) ?>">
                            <span class="nav-icon">🧭</span><span class="nav-text">ABR Search</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/search-text' ? 'is-active' : '' ?>" href="<?= e(url('/search-text')) ?>">
                            <span class="nav-icon">📄</span><span class="nav-text">TBR Search</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/search-content' ? 'is-active' : '' ?>" href="<?= e(url('/search-content')) ?>">
                            <span class="nav-icon">🎞️</span><span class="nav-text">CBR Search</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPath === '/history' ? 'is-active' : '' ?>" href="<?= e(url('/history')) ?>">
                            <span class="nav-icon">📜</span><span class="nav-text">History</span>
                        </a>
                    </li>
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
            <div class="header-meta">
                <span class="status-chip">Live Console</span>
                <div class="badge-display">
                    <span class="badge-label">Current Rank</span>
                    <span class="current-badge"><?= e((string) $currentBadge) ?></span>
                </div>
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
<script src="<?= e(asset('js/fetch.js')) ?>"></script>
<script src="<?= e(asset('js/validate.js')) ?>"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
