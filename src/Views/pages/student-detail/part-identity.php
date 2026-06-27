<!-- ── Identity + Derived + Files summary ─────────────── -->
<section class="detail-grid">
    <article>
        <h3>Identity</h3>
        <dl class="edit-field-list">
            <div class="edit-field-row">
                <dt>Full Name</dt>
                <dd><?php if ($isOwner): ?><input type="text" name="full_name" value="<?= e($student['full_name']) ?>" class="inline-input" form="student-update-form" required><?php else: ?><strong><?= e(mb_strtoupper($student['full_name'])) ?></strong><?php endif; ?></dd>
            </div>
            <div class="edit-field-row">
                <dt>IC Number</dt>
                <dd><?= e($student['ic_masked']) ?></dd>
            </div>
            <div class="edit-field-row">
                <dt>Phone</dt>
                <dd><?php if ($isOwner): ?><input type="tel" name="phone" value="<?= e($student['phone']) ?>" class="inline-input" form="student-update-form" required pattern="(\+?60|0)[0-9\-\s]{7,11}" maxlength="16" title="Malaysian phone number, e.g. 012-3456789"><?php else: ?><?= e($student['phone']) ?><?php endif; ?></dd>
            </div>
            <div class="edit-field-row">
                <dt>Email</dt>
                <dd><?php if ($isOwner): ?><input type="email" name="email" value="<?= e($student['email']) ?>" class="inline-input" form="student-update-form" required><?php else: ?><?= e($student['email']) ?><?php endif; ?></dd>
            </div>
            <div class="edit-field-row">
                <dt>Email Type</dt>
                <dd><?= $student['email_category'] !== null ? '<span class="tag-pill">' . e($student['email_category']) . '</span>' : '—' ?></dd>
            </div>
        </dl>
    </article>
    <article>
        <h3>Derived Attributes</h3>
        <p>Date of Birth: <?= fmt_date($student['date_of_birth']) ?></p>
        <p>Age: <?= ($student['date_of_birth'] !== null && (int) $student['current_age'] > 0) ? e((string) $student['current_age']) . ' years' : '—' ?></p>
        <p>Gender: <?= $student['gender'] === 'M' ? 'Male' : ($student['gender'] === 'F' ? 'Female' : '—') ?></p>
        <p>State of Birth: <?= e($student['state_of_birth'] ?? '—') ?></p>
        <p>Badge: <strong><?= badge_icon($student['stored_badge']) ?></strong></p>
    </article>
    <article>
        <h3>File Summary</h3>
        <p>Total Files: <?= e((string) $student['total_files']) ?></p>
        <p>Photos: <?= e((string) $student['photo_count']) ?></p>
        <p>Audio: <?= e((string) $student['audio_count']) ?></p>
        <p>PDFs: <?= e((string) $student['pdf_count']) ?></p>
        <p>Videos: <?= e((string) $student['video_count']) ?></p>
    </article>
</section>
