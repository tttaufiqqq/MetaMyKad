<?php $pageTitle = 'Page Not Found'; ?>
<?php require src_path('Views/partials/head.php'); ?>
<div class="app-container">
    <div class="main-content" style="grid-column:1/-1;">
        <main class="content">
            <section class="card" style="max-width:640px; margin:4rem auto;">
                <h2>404 — Page Not Found</h2>
                <p style="margin-top:0.5rem;">The page you requested does not exist.</p>
                <div style="margin-top:1.5rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                    <a class="button" href="<?= e(url('/')) ?>">Back to Home</a>
                    <a class="button secondary" href="<?= e(url('/dashboard')) ?>">Dashboard</a>
                </div>
            </section>
        </main>
    </div>
</div>
</body>
</html>
