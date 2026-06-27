<?php if ($isOwner): ?>
<!-- ── Password change (edit mode only) ───────────────── -->
<section class="card">
    <h3>Change Password <span class="field-note">(optional — leave blank to keep current)</span></h3>
    <dl class="edit-field-list" style="margin-top:0.75rem;">
        <div class="edit-field-row">
            <dt>Current</dt>
            <dd><input type="password" name="current_password" class="inline-input" form="student-update-form" autocomplete="current-password" placeholder="Enter current password"></dd>
        </div>
        <div class="edit-field-row">
            <dt>New</dt>
            <dd><input type="password" name="new_password" class="inline-input" form="student-update-form" autocomplete="new-password" placeholder="Enter new password"></dd>
        </div>
    </dl>
</section>
<?php endif; ?>
