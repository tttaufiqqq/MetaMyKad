<?php use MetaMyKad\Core\Auth; ?>

<?php if (empty($students)): ?>
<section class="card">
    <p class="muted">No students found.
        <?php if ($name === '' && $badge === '' && !Auth::check()): ?>
        <a href="<?= e(url('/register')) ?>">Register the first student.</a>
        <?php endif; ?>
    </p>
</section>
<?php else: ?>
<div class="students-grid">
    <?php foreach ($students as $student): ?>
    <?php
        $isRegistered = $student['metamykad_id'] !== null;
        $isEmbed      = ($embed ?? false);
        $cardExtra    = '';
        // Logged-in users must not be linked to another student's /register page.
        // Only anonymous visitors get the self-registration link for unregistered cards.
        if ($isRegistered) {
            $cardTag = 'a';
            if ($isEmbed) {
                // In embed mode: break out of iframe. If not logged in, route via login first.
                $target = Auth::check()
                    ? url('/student-detail?id=' . $student['metamykad_id'])
                    : url('/login?matric=' . urlencode($student['matric_no']) . '&redirect=' . urlencode('/student-detail?id=' . $student['metamykad_id']));
                $cardHref  = 'href="' . e($target) . '"';
                $cardExtra = 'data-embed-out';
            } else {
                $cardHref = 'href="' . e(url('/student-detail?id=' . $student['metamykad_id'])) . '"';
            }
        } elseif (!Auth::check()) {
            $cardTag   = 'a';
            $cardHref  = 'href="' . e(url('/register?matric=' . urlencode($student['matric_no']))) . '"';
            $cardExtra = $isEmbed ? 'data-embed-out' : '';
        } else {
            $cardTag  = 'div';
            $cardHref = '';
        }
    ?>
    <<?= $cardTag ?> class="student-card <?= $isRegistered ? '' : 'student-card--unregistered' ?>" <?= $cardHref ?> <?= $cardExtra ?>>
        <div class="student-card__photo">
            <?php if ($student['photo_id'] !== null): ?>
            <img src="<?= e(url('/file?id=' . $student['photo_id'])) ?>"
                 alt="<?= e($student['full_name']) ?>"
                 loading="lazy">
            <?php else: ?>
            <span class="student-card__avatar">
                <?= e(mb_strtoupper(mb_substr((string) $student['full_name'], 0, 1))) ?>
            </span>
            <?php endif; ?>
        </div>
        <p class="student-card__name"><?= e($student['full_name']) ?></p>
        <?php if ($isRegistered): ?>
        <p class="student-card__badge"><?= badge_icon((string) $student['badge'], '1rem') ?></p>
        <?php else: ?>
        <p class="student-card__badge student-card__badge--none">Profile Not Complete</p>
        <?php endif; ?>
    </<?= $cardTag ?>>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php if ($embed ?? false): ?>
<script>
document.addEventListener('click', function (e) {
    var link = e.target.closest('a[data-embed-out]');
    if (!link) return;
    e.preventDefault();
    window.parent.postMessage({ type: 'embed-navigate', url: link.href }, '*');
});
</script>
<?php endif; ?>
