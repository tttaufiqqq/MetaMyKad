<?php $pageTitle = 'Page Not Found'; ?>
<?php require src_path('Views/partials/head.php'); ?>
<main class="content">
    <section class="card">
        <h2>404 - Page Not Found</h2>
        <p>The page you requested does not exist.</p>
        <p><a class="button" href="<?= e(url('/')) ?>">Back to home</a></p>
    </section>
</main>
</body>
</html>
