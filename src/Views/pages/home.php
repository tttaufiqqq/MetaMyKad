<section class="home-hero">
    <div class="home-hero__content">
        <span class="home-hero__eyebrow">BITP3353 Multimedia Database &middot; Group GS02</span>
        <h1 class="home-hero__title">
            MetaMyKad<br>
            <span class="home-hero__accent">Student Registry</span>
        </h1>
        <p class="home-hero__desc">
            Register students using IC numbers, upload multimedia files, and search
            the registry with attribute-based, text-based, and content-based retrieval.
        </p>
        <div class="home-hero__ctas">
            <a href="<?= e(url('/register')) ?>" class="button">Register Now</a>
            <a href="<?= e(url('/dashboard')) ?>" class="button secondary">View Dashboard</a>
        </div>
    </div>

    <div class="home-hero__visual">
        <?php if (file_exists(public_path('assets/img/group-photo.jpg'))): ?>
            <img
                src="<?= e(asset('img/group-photo.jpg')) ?>"
                class="home-hero__photo"
                alt="Our Group"
                loading="lazy"
            >
        <?php else: ?>
            <div class="home-hero__placeholder">
                <img src="<?= e(asset('images/nav/camera.png')) ?>" alt="" aria-hidden="true" class="home-hero__placeholder-icon">
                <span>Group photo</span>
                <code>public/assets/img/group-photo.jpg</code>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="home-features">
    <div class="home-features__header">
        <p class="home-features__label">Quick Access</p>
        <button type="button" class="badge-guide-trigger" data-badge-guide-open>
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                <circle cx="6" cy="6" r="5.5" stroke="currentColor"/>
                <path d="M6 5.5v3M6 3.5h.01" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Badge Guide
        </button>
    </div>
    <div class="home-features__grid">

        <a href="<?= e(url('/register')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon">
                <img src="<?= e(asset('images/nav/register.png')) ?>" alt="" aria-hidden="true">
            </span>
            <h3>Registration</h3>
            <p>Register a new student or update existing records with multimedia uploads.</p>
        </a>

        <a href="<?= e(url('/search-attribute')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon home-feature-card__icon--group">
                <img src="<?= e(asset('images/nav/abr.png')) ?>" alt="" aria-hidden="true">
                <img src="<?= e(asset('images/nav/tbr.png')) ?>" alt="" aria-hidden="true">
                <img src="<?= e(asset('images/nav/cbr.png')) ?>" alt="" aria-hidden="true">
            </span>
            <h3>Search Hub</h3>
            <p>ABR, TBR &amp; CBR retrieval &mdash; find students by attribute, text, or content.</p>
        </a>

        <a href="<?= e(url('/dashboard')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon">
                <img src="<?= e(asset('images/nav/dashboard.png')) ?>" alt="" aria-hidden="true">
            </span>
            <h3>Dashboard</h3>
            <p>System metrics, badge distribution, and recent registration activity.</p>
        </a>

        <a href="<?= e(url('/history')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon">
                <img src="<?= e(asset('images/nav/history.png')) ?>" alt="" aria-hidden="true">
            </span>
            <h3>History</h3>
            <p>Full audit log of all student registration events over time.</p>
        </a>

    </div>
</section>
