<?php if ($isOwner): ?>

<!-- ── Upload new file types not yet uploaded ─────────── -->
<?php
$missingTypes = array_diff(['photo', 'audio', 'pdf', 'video'], array_keys($filesByType));
if (!empty($missingTypes)): ?>
<section class="card">
    <h3>Upload Missing Files</h3>
    <p class="muted" style="margin-bottom:1rem;">These file types have not been uploaded yet.</p>
    <div class="upload-grid">
        <?php foreach ($missingTypes as $type): ?>
        <div class="upload-box">
            <span class="upload-icon">
                <?php if (!empty($fileIcons[$type])): ?>
                    <?= $fileIcons[$type] ?>
                <?php else: ?>
                    <span>FILE</span>
                <?php endif; ?>
            </span>
            <p><?= ucfirst($type) ?></p>
            <input type="file" name="<?= e($type) ?>" form="student-update-form"
                   accept="<?= e($fileAccept[$type] ?? '') ?>" style="margin-top:0.5rem;">
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- ── Save All Changes button ────────────────────────── -->
<section class="card" style="text-align:center;">
    <button type="submit" form="student-update-form" class="button" style="min-width:220px; font-size:1rem;">
        Save All Changes
    </button>
</section>

</form><!-- end edit form -->

<form id="logout-form" action="<?= e(url('/logout')) ?>" method="post" style="display:none;">
    <?php require src_path('Views/partials/csrf.php'); ?>
</form>
<form id="delete-account-form" action="<?= e(url('/delete-account')) ?>" method="post" style="display:none;">
    <?php require src_path('Views/partials/csrf.php'); ?>
    <input type="hidden" name="student_id" value="<?= e((string) $student['student_id']) ?>">
</form>

<?php endif; ?>
