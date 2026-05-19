<section class="card">
    <h2>Student Detail</h2>
    <p class="muted">Render identity fields, derived metadata, uploads, and delete actions here.</p>
</section>

<section class="detail-grid">
    <article>
        <h3>Identity</h3>
        <p>Name: Sample Student</p>
        <p>IC: 010101014321</p>
        <p>Email: sample@student.edu.my</p>
    </article>
    <article>
        <h3>Derived Metadata</h3>
        <p>Gender: M</p>
        <p>State: Johor</p>
        <p>Badge: Pelajar</p>
    </article>
    <article>
        <h3>Actions</h3>
        <form action="<?= e(url('/delete-file')) ?>" method="post">
            <?php require src_path('Views/partials/csrf.php'); ?>
            <button class="button" type="submit" data-confirm="Delete this file record?">Delete sample file</button>
        </form>
    </article>
</section>

<section class="table-card">
    <h3>Current Multimedia Records</h3>
    <div class="metadata-grid mt-4">
        <div class="file-card">
            <div class="file-preview">🖼️</div>
            <div class="file-name">passport_photo.jpg</div>
            <div class="file-data">Formal photo<br>Expression: happy<br>MIME: image/jpeg</div>
        </div>
        <div class="file-card">
            <div class="file-preview">🎬</div>
            <div class="file-name">intro_video.mp4</div>
            <div class="file-data">Resolution: FHD<br>Duration: 84 sec<br>MIME: video/mp4</div>
        </div>
    </div>
</section>
