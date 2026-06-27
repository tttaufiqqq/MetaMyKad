<?php
use MetaMyKad\Core\Flash;

$pageTitle = $pageTitle ?? 'MetaMyKad';
$flash = Flash::get();
$currentPath = current_path();
?>
<?php require src_path('Views/partials/head.php'); ?>
<style>
.embed-layout {
    min-height: 100vh;
    background: var(--color-bg);
    display: flex;
    flex-direction: column;
}
.embed-layout__content {
    flex: 1;
    padding: var(--space-3) var(--space-2);
    max-width: 760px;
    margin: 0 auto;
    width: 100%;
    box-sizing: border-box;
}
</style>
<div class="embed-layout">
    <main class="embed-layout__content">
        <?php require src_path('Views/partials/toast.php'); ?>
        <?php require $contentView; ?>
    </main>
</div>
<?php require src_path('Views/partials/confirm-dialog.php'); ?>
<div class="page-spinner" id="page-spinner" aria-hidden="true" role="status" aria-label="Loading">
    <div class="page-spinner__ring"></div>
</div>
<script src="<?= e(asset('js/fetch.js')) ?>"></script>
<script src="<?= e(asset('js/validate.js')) ?>"></script>
<script src="<?= e(asset('js/validate-mutex.js')) ?>"></script>
<script src="<?= e(asset('js/file-input.js')) ?>"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
<script src="<?= e(asset('js/select.js')) ?>"></script>
</body>
</html>
