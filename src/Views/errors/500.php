<?php $pageTitle = 'Server Error'; ?>
<?php require src_path('Views/partials/head.php'); ?>
<main class="content">
    <section class="card">
        <h2>500 - Server Error</h2>
        <p>Something went wrong while processing the request.</p>
        <p><a class="button" href="<?= e(url('/')) ?>">Back to home</a></p>
    </section>
</main>
</body>
</html>
