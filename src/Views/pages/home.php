<section class="home-hero">
    <div class="home-hero__content">
        <span class="home-hero__eyebrow">BITP3353 Multimedia Database</span>
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
                <span style="font-size:2.5rem;">&#x1F4F8;</span>
                <span>Group photo</span>
                <code>public/assets/img/group-photo.jpg</code>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="home-features">
    <p class="home-features__label">Quick Access</p>
    <div class="home-features__grid">

        <a href="<?= e(url('/register')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon">&#x1F4DD;</span>
            <h3>Registration</h3>
            <p>Register a new student or update existing records with multimedia uploads.</p>
        </a>

        <a href="<?= e(url('/search-attribute')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon">&#x1F50D;</span>
            <h3>Search Hub</h3>
            <p>ABR, TBR &amp; CBR retrieval &mdash; find students by attribute, text, or content.</p>
        </a>

        <a href="<?= e(url('/dashboard')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon">&#x1F4CA;</span>
            <h3>Dashboard</h3>
            <p>System metrics, badge distribution, and recent registration activity.</p>
        </a>

        <a href="<?= e(url('/history')) ?>" class="home-feature-card">
            <span class="home-feature-card__icon">&#x1F4DC;</span>
            <h3>History</h3>
            <p>Full audit log of all student registration events over time.</p>
        </a>

    </div>
</section>
