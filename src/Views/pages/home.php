<section class="card">
    <div class="portal-header">
        <h2>MetaMyKad Registry</h2>
        <span class="portal-course-code">BITP3353</span>
    </div>

    <div class="portal-wrap">

        <!-- Left: Navigation cards -->
        <nav class="portal-nav-cards" aria-label="Quick navigation">

            <a class="portal-nav-card" href="<?= e(url('/register')) ?>">
                <span class="portal-nav-card__icon">📝</span>
                <div class="portal-nav-card__body">
                    <span class="portal-nav-card__title">Student Registry</span>
                    <span class="portal-nav-card__sub">Register or re-register a student record</span>
                </div>
            </a>

            <a class="portal-nav-card" href="<?= e(url('/search-attribute')) ?>">
                <span class="portal-nav-card__icon">🔍</span>
                <div class="portal-nav-card__body">
                    <span class="portal-nav-card__title">Search Hub</span>
                    <span class="portal-nav-card__sub">ABR &middot; TBR &middot; CBR Retrieval</span>
                </div>
            </a>

            <a class="portal-nav-card" href="<?= e(url('/dashboard')) ?>">
                <span class="portal-nav-card__icon">📊</span>
                <div class="portal-nav-card__body">
                    <span class="portal-nav-card__title">Dashboard</span>
                    <span class="portal-nav-card__sub">Metrics, badges &amp; recent activity</span>
                </div>
            </a>

            <a class="portal-nav-card" href="<?= e(url('/history')) ?>">
                <span class="portal-nav-card__icon">📜</span>
                <div class="portal-nav-card__body">
                    <span class="portal-nav-card__title">History</span>
                    <span class="portal-nav-card__sub">Registration audit log</span>
                </div>
            </a>

        </nav>

        <!-- Right: Welcome panel -->
        <div class="portal-welcome">

            <!-- Group photo — place your image at public/assets/img/group-photo.jpg -->
            <?php if (file_exists(public_path('assets/img/group-photo.jpg'))): ?>
                <img
                    src="<?= e(asset('img/group-photo.jpg')) ?>"
                    class="group-photo"
                    alt="Our Group"
                    loading="lazy"
                >
            <?php else: ?>
                <div class="group-photo-placeholder">
                    <span style="font-size:2rem;">📸</span>
                    <span>Group photo placeholder</span>
                    <span style="font-size:0.75rem;">Place your photo at<br><code>public/assets/img/group-photo.jpg</code></span>
                </div>
            <?php endif; ?>

            <div class="portal-desc">
                <p>
                    Welcome to <strong>MetaMyKad</strong> — a multimedia student registry built for
                    <strong>BITP3353 Multimedia Database</strong>.
                </p>
                <p style="margin-top:0.75rem;">
                    Register students using their IC number, upload supporting multimedia files
                    (photo, audio, PDF, video), and search the registry using
                    <strong>attribute-based</strong>, <strong>text-based</strong>, and
                    <strong>content-based</strong> retrieval methods.
                </p>
                <p style="margin-top:0.75rem;">
                    Students can <a href="<?= e(url('/login')) ?>" style="color:var(--color-brand-cyan,#39c5ff);">log in</a>
                    with their matric number to view and update their own profile.
                </p>
            </div>

        </div>

    </div>
</section>
