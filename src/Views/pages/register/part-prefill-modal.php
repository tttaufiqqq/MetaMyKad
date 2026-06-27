<?php if (!empty($prefill)): ?>
<div class="confirm-dialog hidden" id="welcome-modal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="confirm-dialog__panel">
        <h3>Complete Your Profile</h3>
        <p>
            Welcome, <strong><?= e($prefill['full_name']) ?></strong>!
            Your student identity has been verified.
        </p>
        <p style="margin-top:.75rem;">
            To use MetaMyKad, please fill in the fields below &mdash;
            your <strong>IC number</strong>, <strong>email</strong>, and
            <strong>multimedia file uploads</strong> are required to activate your profile.
        </p>
        <div class="confirm-dialog__actions">
            <button type="button" class="button" id="welcome-modal-close">Got it, let's go</button>
        </div>
    </div>
</div>
<?php endif; ?>
