<section class="card">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
        <div>
            <h2>All Students <span style="font-size:1.5rem; font-weight:700; color:var(--color-text); margin-left:0.4rem;">(<?= count($students) ?>)</span></h2>
            <p class="muted" style="margin-bottom:0.5rem;"><?= $registered ?> of <?= count($students) ?> students have a MetaMyKad profile.</p>
            <button type="button" class="badge-guide-trigger" data-badge-guide-open>
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <circle cx="6" cy="6" r="5.5" stroke="currentColor"/>
                    <path d="M6 5.5v3M6 3.5h.01" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
                Badge Guide
            </button>
        </div>
        <?php if (!\MetaMyKad\Core\Auth::check()): ?>
        <a class="button" href="<?= e(url('/register')) ?>">Register Student</a>
        <?php endif; ?>
    </div>
</section>
