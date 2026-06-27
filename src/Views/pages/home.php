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
        <button type="button" id="onboard-trigger" class="badge-guide-trigger" style="margin-top:1.25rem;">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                <circle cx="6" cy="6" r="5.5" stroke="currentColor"/>
                <path d="M4.2 4.5a1.8 1.8 0 0 1 3.6 0c0 1.2-1.8 1.5-1.8 2.8" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M6 9h.01" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
            How to use this?
        </button>
        <p class="home-hero__notice">
            Already registered in <strong>Madam Hidayah's system</strong>?
            <?php if (\MetaMyKad\Core\Auth::check()): ?>
                <?php $__u = \MetaMyKad\Core\Auth::user(); ?>
                <a href="<?= e(url('/student-detail?id=' . $__u['id'])) ?>" class="home-notice-link"><strong>Go to your profile</strong></a> or
            <?php else: ?>
                <a href="<?= e(url('/login')) ?>" data-home-embed class="home-notice-link"><strong>Log in to edit your profile</strong></a> or
            <?php endif; ?>
            <a href="<?= e(url('/students')) ?>" data-home-embed class="home-notice-link"><strong>Browse the student list</strong></a>.
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

<div id="home-embed-modal" class="home-embed-modal hidden" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="home-embed-modal__bar">
        <span class="home-embed-modal__label" id="home-embed-label"></span>
        <button type="button" id="home-embed-fullpage" class="home-embed-modal__fullpage">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            Full Page
        </button>
        <button type="button" id="home-embed-close" class="home-embed-modal__close" aria-label="Close">&times;</button>
    </div>
    <iframe id="home-embed-frame" class="home-embed-modal__frame" src="" title="Page content" frameborder="0"></iframe>
</div>
<style>
.home-embed-modal { position: fixed; inset: 0; z-index: 200; background: rgba(0,0,0,0.82); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1rem; }
.home-embed-modal.hidden { display: none; }
.home-embed-modal__bar { width: min(960px,100%); display: flex; align-items: center; padding-bottom: 0.5rem; gap: 0.6rem; }
.home-embed-modal__label { flex: 1; font-size: 0.72rem; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(255,255,255,0.35); }
.home-embed-modal__fullpage { background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.14); color: rgba(255,255,255,0.7); border-radius: 6px; padding: 0 0.65rem; height: 2rem; font-size: 0.72rem; font-weight: 600; letter-spacing: 0.04em; cursor: pointer; display: flex; align-items: center; gap: 5px; white-space: nowrap; }
.home-embed-modal__fullpage:hover { background: rgba(255,255,255,0.13); color: #fff; }
.home-embed-modal__close { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14); color: #fff; border-radius: 6px; width: 2rem; height: 2rem; font-size: 1.3rem; cursor: pointer; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.home-embed-modal__close:hover { background: rgba(255,255,255,0.16); }
.home-embed-modal__frame { width: min(960px,100%); height: min(780px,88vh); border: none; border-radius: 12px; background: var(--color-bg,#080e21); }
.home-notice-link { font-weight: 700; color: var(--color-brand); text-decoration: underline; text-underline-offset: 3px; }
.home-notice-link:hover { color: #fff; }
.home-notice-link strong { font-weight: 800; }
</style>
<script>
(function () {
    var modal       = document.getElementById('home-embed-modal');
    var frame       = document.getElementById('home-embed-frame');
    var closeBtn    = document.getElementById('home-embed-close');
    var fullPageBtn = document.getElementById('home-embed-fullpage');
    var label       = document.getElementById('home-embed-label');
    if (!modal || !frame) return;
    var baseHref = '/';

    function openEmbed(url, title, base) {
        baseHref = base || url;
        frame.src = url;
        if (label) label.textContent = title || '';
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
    function closeEmbed() {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        frame.src = '';
        document.body.style.overflow = '';
    }

    closeBtn.addEventListener('click', closeEmbed);
    if (fullPageBtn) fullPageBtn.addEventListener('click', function () { closeEmbed(); window.location.href = baseHref; });
    modal.addEventListener('click', function (e) { if (e.target === modal) closeEmbed(); });

    document.addEventListener('click', function (e) {
        var link = e.target.closest('[data-home-embed]');
        if (!link) return;
        e.preventDefault();
        var href = link.getAttribute('href') || '';
        openEmbed(href + (href.indexOf('?') === -1 ? '?' : '&') + 'embed=1', link.textContent.trim(), href);
    });
    window.addEventListener('message', function (e) {
        if (!e.data) return;
        if (e.data.type === 'embed-redirect' || e.data.type === 'embed-navigate') {
            closeEmbed(); window.location.href = e.data.url;
        }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeEmbed();
    });
}());
</script>
