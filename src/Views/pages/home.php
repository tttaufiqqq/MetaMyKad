<section class="home-hero">
    <div class="home-hero__content">
        <span class="home-hero__eyebrow">BITP3353 Multimedia Database &middot; Group GS02</span>
        <h1 class="home-hero__title">
            MetaMyKad<br>
            <span class="home-hero__accent">Student Registry</span>
        </h1>
        <p class="home-hero__desc">
            Get metadata about students from their IC numbers and explore the metadata about the multimedia files they've uploaded.
            Search the registry with attribute-based, text-based, and content-based retrieval.
        </p>
        <div class="home-hero__ctas">
            <a href="<?= e(url('/register')) ?>" class="button">Register Now</a>
            <a href="<?= e(url('/dashboard')) ?>" class="button secondary">View Dashboard</a>
        </div>
        <p class="home-hero__notice">
            Already registered in <strong>Madam Hidayah's system</strong>?
            <a href="<?= e(url('/login')) ?>">Log in to edit your profile</a> or
            <a href="<?= e(url('/students')) ?>">browse the student list</a>.
        </p>
    </div>

    <div class="home-hero__visual">
        <?php if (file_exists(public_path('assets/images/group-photo.jpg'))): ?>
            <img
                src="<?= e(asset('images/group-photo.jpg')) ?>"
                class="home-hero__photo"
                alt="Our Group"
                loading="lazy"
            >
        <?php else: ?>
            <div class="home-hero__placeholder">
                <span class="home-hero__placeholder-icon" aria-hidden="true"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></span>
                <span>Group photo</span>
                <code>public/assets/images/group-photo.jpg</code>
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
            <span class="home-feature-card__icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            </span>
            <h3>Registration</h3>
            <p>Register a new student or update existing records with multimedia uploads.</p>
        </a>

        <a href="<?= e(url('/search-attribute')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon home-feature-card__icon--group" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/><line x1="11" y1="8" x2="11" y2="14"/></svg>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="9" x2="14" y2="9"/><line x1="8" y1="13" x2="12" y2="13"/></svg>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><rect x="7" y="8" width="3" height="3" rx="0.5"/><polyline points="11 14 13 12 15 14"/></svg>
            </span>
            <h3>Search Hub</h3>
            <p>ABR, TBR &amp; CBR retrieval &mdash; find students by attribute, text, or content.</p>
        </a>

        <a href="<?= e(url('/dashboard')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            </span>
            <h3>Dashboard</h3>
            <p>System metrics, badge distribution, and recent registration activity.</p>
        </a>

        <a href="<?= e(url('/history')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/></svg>
            </span>
            <h3>History</h3>
            <p>Full audit log of all student registration events over time.</p>
        </a>

    </div>
</section>
