<?php if (is_array($flash ?? null)): ?>
    <div class="toast toast-<?= e($flash['type']) ?>" data-toast>
        <span><?= e($flash['message']) ?></span>
        <button type="button" class="toast-close" data-toast-close aria-label="Close notification">x</button>
    </div>
<?php endif; ?>
