<?php
/** @var array $student */
/** @var array $files */
/** @var array $history */

$fileIcons = ['photo' => '🖼️', 'audio' => '🎵', 'pdf' => '📄', 'video' => '🎬'];

function fmt_bytes(int $bytes): string {
    if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
    if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
    return $bytes . ' B';
}
?>

<section class="card">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
        <div>
            <h2><?= e($student['full_name']) ?></h2>
            <p class="muted">IC: <?= e($student['ic_number']) ?> &nbsp;|&nbsp; Badge: <strong><?= e($student['stored_badge']) ?></strong></p>
        </div>
        <a class="button secondary" href="<?= e(url('/re-register')) ?>">Re-Register</a>
    </div>
</section>

<section class="detail-grid">
    <article>
        <h3>Identity</h3>
        <p>Full Name: <strong><?= e($student['full_name']) ?></strong></p>
        <p>IC Number: <?= e($student['ic_number']) ?></p>
        <p>Phone: <?= e($student['phone']) ?></p>
        <p>Email: <?= e($student['email']) ?></p>
        <p>Email Type: <?= e($student['email_category']) ?></p>
    </article>
    <article>
        <h3>Derived Attributes</h3>
        <p>Date of Birth: <?= e($student['date_of_birth']) ?></p>
        <p>Age: <?= e((string) $student['current_age']) ?> years</p>
        <p>Gender: <?= $student['gender'] === 'M' ? 'Male' : 'Female' ?></p>
        <p>State of Birth: <?= e($student['state_of_birth']) ?></p>
        <p>Badge: <strong><?= e($student['stored_badge']) ?></strong></p>
    </article>
    <article>
        <h3>File Summary</h3>
        <p>Total Files: <?= e((string) $student['total_files']) ?></p>
        <p>Photos: <?= e((string) $student['photo_count']) ?></p>
        <p>Audio: <?= e((string) $student['audio_count']) ?></p>
        <p>PDFs: <?= e((string) $student['pdf_count']) ?></p>
        <p>Videos: <?= e((string) $student['video_count']) ?></p>
    </article>
</section>

<?php if (empty($files)): ?>
<section class="card">
    <p class="muted">No files uploaded for this student.</p>
</section>
<?php else: ?>
<section class="table-card">
    <h3>Uploaded Files</h3>
    <div class="metadata-grid mt-4">
    <?php foreach ($files as $file): ?>
        <?php $cbr = $file['cbr'] ?? []; $tags = $file['tags'] ?? []; ?>
        <div class="file-card">
            <div class="file-preview"><?= $fileIcons[$file['file_type']] ?? '📁' ?></div>
            <div class="file-name"><?= e($file['filename']) ?></div>
            <div class="file-data">
                Type: <?= e($file['mime_type']) ?><br>
                Size: <?= e(fmt_bytes((int) $file['file_size'])) ?><br>
                Date: <?= e($file['upload_date']) ?>

                <?php if ($file['file_type'] === 'photo' && $cbr !== []): ?>
                    <br><br>Category: <?= e($cbr['photo_category'] ?? '—') ?>
                    <br>Expression: <?= e($cbr['dominant_expression'] ?? '—') ?>
                    <?php if (isset($cbr['expression_confidence'])): ?>
                        (<?= e(round((float) $cbr['expression_confidence'] * 100)) ?>%)
                    <?php endif; ?>
                    <br>BG Color: <?= e($cbr['dominant_bg_color'] ?? '—') ?>
                <?php elseif ($file['file_type'] === 'audio' && $cbr !== []): ?>
                    <br><br>Duration: <?= e((string) ($cbr['audio_duration_sec'] ?? '—')) ?>s
                    (<?= e($cbr['audio_duration_tier'] ?? '—') ?>)
                    <br>Bitrate: <?= e((string) ($cbr['audio_bitrate'] ?? '—')) ?> kbps
                <?php elseif ($file['file_type'] === 'video' && $cbr !== []): ?>
                    <br><br>Resolution: <?= e($cbr['video_resolution'] ?? '—') ?>
                    (<?= e($cbr['video_resolution_tier'] ?? '—') ?>)
                    <br>Duration: <?= e((string) ($cbr['video_duration_sec'] ?? '—')) ?>s
                <?php elseif ($file['file_type'] === 'pdf'): ?>
                    <?php if (!empty($file['extracted_text'])): ?>
                        <br><br>Text preview: <?= e(mb_strimwidth((string) $file['extracted_text'], 0, 200, '…')) ?>
                    <?php else: ?>
                        <br><br>Text: not extracted
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!empty($tags)): ?>
                    <br><br>
                    <?php foreach ($tags as $tag): ?>
                        <span class="tag-pill"><?= e($tag['tag_name']) ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <form action="<?= e(url('/delete-file')) ?>" method="post" style="margin-top:0.75rem;">
                <?php require src_path('Views/partials/csrf.php'); ?>
                <input type="hidden" name="file_id" value="<?= e((string) $file['id']) ?>">
                <button class="button secondary"
                        type="submit"
                        data-confirm="Delete this <?= e($file['file_type']) ?> file? This cannot be undone."
                        style="width:100%; font-size:0.76rem; padding:0.5rem 0.75rem;">
                    Delete
                </button>
            </form>
        </div>
    <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($history)): ?>
<section class="table-card">
    <h3>Registration History</h3>
    <table>
        <thead>
        <tr>
            <th>Action</th>
            <th>Registered At</th>
            <th>Files At Time</th>
            <th>Badge At Time</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($history as $entry): ?>
        <tr>
            <td><?= e($entry['action']) ?></td>
            <td><?= e($entry['registered_at']) ?></td>
            <td><?= e((string) $entry['files_uploaded']) ?></td>
            <td><?= e($entry['badge_at_time']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>
