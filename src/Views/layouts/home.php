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
            <li><a href="<?= e(url('/re-register')) ?>" class="topnav__link">Re-Register</a></li>
            <li class="topnav__dropdown-wrap">
                <button class="topnav__link topnav__dropdown-btn" id="topnav-search-btn" type="button">
                    Search <span class="topnav__caret">&#x25BE;</span>
                </button>
                <ul class="topnav__dropdown" id="topnav-search-dd">
                    <li>
                        <a href="<?= e(url('/search-attribute')) ?>" class="topnav__dropdown-link topnav__dropdown-link--icon">
                            <img src="<?= e(asset('images/nav/abr.png')) ?>" alt="" aria-hidden="true" class="topnav__dropdown-icon">
                            <span>ABR Search</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= e(url('/search-text')) ?>" class="topnav__dropdown-link topnav__dropdown-link--icon">
                            <img src="<?= e(asset('images/nav/tbr.png')) ?>" alt="" aria-hidden="true" class="topnav__dropdown-icon">
                            <span>TBR Search</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= e(url('/search-content')) ?>" class="topnav__dropdown-link topnav__dropdown-link--icon">
                            <img src="<?= e(asset('images/nav/cbr.png')) ?>" alt="" aria-hidden="true" class="topnav__dropdown-icon">
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
                    &#x1F464; <?= e(strtoupper(Auth::user()['full_name'])) ?>
                </a>
                <form action="<?= e(url('/logout')) ?>" method="post" style="display:inline;margin:0;">
                    <?php require src_path('Views/partials/csrf.php'); ?>
                    <button type="submit" class="topnav__btn topnav__btn--ghost">Sign Out</button>
                </form>
            <?php else: ?>
                <a href="<?= e(url('/login')) ?>" class="topnav__btn">Student Login</a>
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
