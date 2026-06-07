<?php
/** @var \Throwable $exception */
/** @var bool $debug */
$pageTitle = 'Server Error';
?>
<?php require src_path('Views/partials/head.php'); ?>
<div class="app-container">
    <div class="main-content" style="grid-column:1/-1;">
        <main class="content">
            <section class="card" style="max-width:640px; margin:4rem auto;">
                <h2 style="color:var(--color-danger,#ef4444);">500 — Server Error</h2>
                <p style="margin-top:0.5rem;">Something went wrong while processing the request.</p>
                <p class="muted" style="margin-top:0.25rem;">The error has been logged. Please try again or contact the administrator.</p>
                <div style="margin-top:1.5rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                    <a class="button" href="<?= e(url('/')) ?>">Back to Home</a>
                    <a class="button secondary" href="<?= e(url('/dashboard')) ?>">Dashboard</a>
                </div>
            </section>

            <?php if (isset($debug) && $debug && isset($exception)): ?>
            <section class="card" style="max-width:640px; margin:0 auto 2rem; font-size:0.78rem;">
                <h3 style="color:var(--color-danger,#ef4444); margin-bottom:0.5rem;">Debug Info</h3>
                <p><strong>Exception:</strong> <?= e(get_class($exception)) ?></p>
                <p><strong>Message:</strong> <?= e($exception->getMessage()) ?></p>
                <p><strong>File:</strong> <?= e($exception->getFile()) ?>:<?= e((string) $exception->getLine()) ?></p>
                <details style="margin-top:0.75rem;">
                    <summary style="cursor:pointer; color:var(--color-muted);">Stack trace</summary>
                    <pre style="overflow:auto; font-size:0.7rem; margin-top:0.5rem; color:var(--color-dim);"><?= e($exception->getTraceAsString()) ?></pre>
                </details>
            </section>
            <?php endif; ?>
        </main>
    </div>
</div>
</body>
</html>
