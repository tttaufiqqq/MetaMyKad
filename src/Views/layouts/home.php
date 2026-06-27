<?php

use MetaMyKad\Core\Flash;
use MetaMyKad\Core\Auth;

$pageTitle   = $pageTitle ?? 'MetaMyKad';
$flash       = Flash::get();
$currentPath = current_path();
?>
<?php require src_path('Views/partials/head.php'); ?>
<div class="home-layout">

    <nav class="topnav" id="topnav">
        <a class="topnav__brand" href="<?= e(url('/')) ?>">
            <img src="<?= e(asset('images/favicon.png')) ?>" alt="MetaMyKad" class="topnav__logo">
            <span class="topnav__wordmark"><?= e((string) config('app.name', 'MetaMyKad')) ?></span>
            <span class="topnav__divider"></span>
            <span class="topnav__subtitle">BITP3353</span>
        </a>

        <ul class="topnav__links" id="topnav-links">
            <li><a href="<?= e(url('/dashboard')) ?>" class="topnav__link">Dashboard</a></li>
            <li><a href="<?= e(url('/register')) ?>" class="topnav__link">Register</a></li>
            <li class="topnav__dropdown-wrap">
                <button class="topnav__link topnav__dropdown-btn" id="topnav-search-btn" type="button">
                    Search <span class="topnav__caret">&#x25BE;</span>
                </button>
                <ul class="topnav__dropdown" id="topnav-search-dd">
                    <li>
                        <a href="<?= e(url('/search-attribute')) ?>" class="topnav__dropdown-link topnav__dropdown-link--icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="topnav__dropdown-icon"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/><line x1="11" y1="8" x2="11" y2="14"/></svg>
                            <span>ABR Search</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= e(url('/search-text')) ?>" class="topnav__dropdown-link topnav__dropdown-link--icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="topnav__dropdown-icon"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="9" x2="14" y2="9"/><line x1="8" y1="13" x2="12" y2="13"/></svg>
                            <span>TBR Search</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= e(url('/search-content')) ?>" class="topnav__dropdown-link topnav__dropdown-link--icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="topnav__dropdown-icon"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><rect x="7" y="8" width="3" height="3" rx="0.5"/><polyline points="11 14 13 12 15 14"/></svg>
                            <span>CBR Search</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li><a href="<?= e(url('/history')) ?>" class="topnav__link">History</a></li>
        </ul>

        <div class="topnav__user">
            <?php if (Auth::check()): ?>
                <a href="<?= e(url('/student-detail?id=' . Auth::user()['id'])) ?>" class="topnav__user-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="vertical-align:middle;margin-right:0.35rem;opacity:0.85;flex-shrink:0;"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                    <?= e(strtoupper(Auth::user()['full_name'])) ?>
                </a>
                <form action="<?= e(url('/logout')) ?>" method="post" style="display:inline;margin:0;">
                    <?php require src_path('Views/partials/csrf.php'); ?>
                    <button type="submit" class="topnav__btn topnav__btn--ghost">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="vertical-align:middle;margin-right:0.3rem;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Sign Out
                    </button>
                </form>
            <?php else: ?>
                <a href="<?= e(url('/login')) ?>" class="topnav__btn">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="vertical-align:middle;margin-right:0.3rem;"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>Student Login
                </a>
            <?php endif; ?>
        </div>

        <button class="topnav__toggle" id="topnav-toggle" type="button" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>
    </nav>

    <main class="home-main">
        <?php require src_path('Views/partials/toast.php'); ?>
        <?php require $contentView; ?>
    </main>

    <footer class="status-footer home-footer">
        <div>METAMYKAD | BITP3353 MULTIMEDIA DATABASE</div>
    </footer>

</div>
<?php require src_path('Views/partials/badge-guide-modal.php'); ?>
<?php require src_path('Views/partials/onboarding-modal.php'); ?>
<?php require src_path('Views/partials/confirm-dialog.php'); ?>
<div class="page-spinner" id="page-spinner" aria-hidden="true" role="status" aria-label="Loading">
    <div class="page-spinner__ring"></div>
</div>
<script src="<?= e(asset('js/fetch.js')) ?>"></script>
<script src="<?= e(asset('js/validate.js')) ?>"></script>
<script src="<?= e(asset('js/file-input.js')) ?>"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
<script>
(function () {
    var toggle = document.getElementById('topnav-toggle');
    var nav    = document.getElementById('topnav');
    var ddBtn  = document.getElementById('topnav-search-btn');
    var dd     = document.getElementById('topnav-search-dd');

    toggle && toggle.addEventListener('click', function () {
        nav.classList.toggle('is-open');
    });

    ddBtn && ddBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dd.classList.toggle('is-open');
    });

    document.addEventListener('click', function () {
        dd && dd.classList.remove('is-open');
        nav && nav.classList.remove('is-open');
    });
}());
</script>
</body>
</html>
