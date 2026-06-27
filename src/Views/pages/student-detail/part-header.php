<?php if ($isStub): ?>
<section class="card" style="border-left:4px solid #f97316; background:rgba(249,115,22,.06);">
    <div style="display:flex; align-items:flex-start; gap:0.75rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            <strong>Profile incomplete.</strong>
            Your account was created from the student system but still needs your IC number, email, and multimedia files.
            <a href="<?= e(url('/register')) ?>" class="button" style="margin-left:1rem; font-size:.85rem; padding:.3rem .8rem;">Complete Registration &rarr;</a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($isOwner): ?>
<form id="student-update-form" action="<?= e(url('/student-update')) ?>" method="post" enctype="multipart/form-data" data-validate>
    <?php require src_path('Views/partials/csrf.php'); ?>
    <input type="hidden" name="student_id" value="<?= e((string) $student['student_id']) ?>">
<?php endif; ?>

<!-- ── Header card ─────────────────────────────────────── -->
<section class="card">
    <div class="card-header-row">
        <div>
            <h2 class="detail-name-heading"><?= e($student['full_name']) ?></h2>
            <p class="muted">Badge: <strong><?= badge_icon($student['stored_badge']) ?></strong></p>
        </div>
        <div class="owner-actions">
            <?php if ($isOwner): ?>
                <button type="submit" form="logout-form" class="button secondary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Sign Out
                </button>
                <button type="submit" form="delete-account-form"
                        class="button secondary button--danger"
                        data-confirm="Delete your account? This will permanently remove all your data and cannot be undone.">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    Delete Account
                </button>
            <?php else: ?>
                <a class="button" href="<?= e(url('/login?matric=' . urlencode($student['matric_number'] ?? ''))) ?>">Login to Edit</a>
            <?php endif; ?>
        </div>
    </div>
</section>
