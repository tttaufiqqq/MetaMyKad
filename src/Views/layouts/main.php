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
        <div class="logo">
            <span class="logo-mark">MK</span>
            <span><?= e((string) config('app.name', 'MetaMyKad')) ?></span>
        </div>
        <ul class="nav-list">
            <li class="nav-item">
                <a class="nav-link <?= $currentPath === '/' ? 'is-active' : '' ?>" href="<?= e(url('/')) ?>">
                    <span>🏠</span><span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPath === '/dashboard' ? 'is-active' : '' ?>" href="<?= e(url('/dashboard')) ?>">
                    <span>📊</span><span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPath === '/register' ? 'is-active' : '' ?>" href="<?= e(url('/register')) ?>">
                    <span>📝</span><span>Registration</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPath === '/re-register' ? 'is-active' : '' ?>" href="<?= e(url('/re-register')) ?>">
                    <span>♻️</span><span>Re-Registration</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPath === '/search-attribute' ? 'is-active' : '' ?>" href="<?= e(url('/search-attribute')) ?>">
                    <span>🧭</span><span>ABR Search</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPath === '/search-text' ? 'is-active' : '' ?>" href="<?= e(url('/search-text')) ?>">
                    <span>📄</span><span>TBR Search</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPath === '/search-content' ? 'is-active' : '' ?>" href="<?= e(url('/search-content')) ?>">
                    <span>🎞️</span><span>CBR Search</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPath === '/history' ? 'is-active' : '' ?>" href="<?= e(url('/history')) ?>">
                    <span>📜</span><span>History</span>
                </a>
            </li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="site-header">
            <div>
                <p class="eyebrow">MyKad-Driven Multimedia Registry</p>
                <h1 class="page-title"><?= e($pageTitle) ?></h1>
            </div>
            <div class="badge-display">
                <span class="badge-label">Current Rank</span>
                <span class="current-badge"><?= e((string) $currentBadge) ?></span>
            </div>
        </header>
        <main class="content">
            <?php require src_path('Views/partials/toast.php'); ?>
            <?php require $contentView; ?>
        </main>
        <footer class="status-footer">
            <div style="display:flex; gap:20px; flex-wrap:wrap;">
                <span>SYSTEM STATUS: <span class="status-online">OPERATIONAL</span></span>
                <span>STACK: PURE PHP + MYSQL</span>
            </div>
            <div>METAMYKAD // BACKEND-FIRST GROUP WORKFLOW</div>
        </footer>
    </div>
</div>
<?php require src_path('Views/partials/confirm-dialog.php'); ?>
<script src="<?= e(asset('js/fetch.js')) ?>"></script>
<script src="<?= e(asset('js/validate.js')) ?>"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
