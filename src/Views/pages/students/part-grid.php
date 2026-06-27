<?php use MetaMyKad\Core\Auth; ?>

<?php if (empty($students)): ?>
<section class="card">
    <p class="muted">No students found.
        <?php if ($name === '' && $badge === ''): ?>
        <a href="<?= e(url('/register')) ?>">Register the first student.</a>
        <?php endif; ?>
    </p>
</section>
<?php else: ?>
<div class="students-grid">
    <?php foreach ($students as $student): ?>
    <?php
        $isRegistered = $student['metamykad_id'] !== null;
        // Logged-in users must not be linked to another student's /register page.
        // Only anonymous visitors get the self-registration link for unregistered cards.
        if ($isRegistered) {
            $cardTag  = 'a';
            $cardHref = 'href="' . e(url('/student-detail?id=' . $student['metamykad_id'])) . '"';
        } elseif (!Auth::check()) {
            $cardTag  = 'a';
            $cardHref = 'href="' . e(url('/register?matric=' . urlencode($student['matric_no']))) . '"';
        } else {
            $cardTag  = 'div';
            $cardHref = '';
        }
    ?>
    <<?= $cardTag ?> class="student-card <?= $isRegistered ? '' : 'student-card--unregistered' ?>" <?= $cardHref ?>>
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
