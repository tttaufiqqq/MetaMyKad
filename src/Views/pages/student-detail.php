<?php
/** @var array $student */
/** @var array $files */
/** @var array $history */

use MetaMyKad\Core\Auth;

$isOwner = Auth::check() && (int) Auth::user()['id'] === (int) $student['student_id'];

$fileIcons = [
    'photo' => asset('images/nav/replace-image.png'),
    'audio' => asset('images/nav/replace-audio.png'),
    'pdf'   => asset('images/nav/replace-pdf.png'),
    'video' => asset('images/nav/replace-video.png'),
];

function fmt_bytes(int $bytes): string {
    if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
    if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
    return $bytes . ' B';
}

function fmt_seconds(int $s): string {
    $m = intdiv($s, 60);
    return $m . ':' . str_pad((string) ($s % 60), 2, '0', STR_PAD_LEFT);
}

// Index files by type for easy lookup in edit form
$filesByType = [];
foreach ($files as $f) {
    $filesByType[$f['file_type']] = $f;
}
?>

<?php if ($isOwner): ?>
<form id="student-update-form" action="<?= e(url('/student-update')) ?>" method="post" enctype="multipart/form-data">
    <?php require src_path('Views/partials/csrf.php'); ?>
    <input type="hidden" name="student_id" value="<?= e((string) $student['student_id']) ?>">
<?php endif; ?>

<!-- ── Header card ─────────────────────────────────────── -->
<section class="card">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
        <div>
            <h2><?= e($student['full_name']) ?></h2>
            <p class="muted">IC: <?= e($student['ic_number']) ?> &nbsp;|&nbsp; Badge: <strong><?= e($student['stored_badge']) ?></strong></p>
        </div>
        <div class="owner-actions">
            <?php if ($isOwner): ?>
                <form action="<?= e(url('/logout')) ?>" method="post" style="margin:0;">
                    <?php require src_path('Views/partials/csrf.php'); ?>
                    <button type="submit" class="button secondary">Sign Out</button>
                </form>
                <form id="delete-account-form" action="<?= e(url('/delete-account')) ?>" method="post" style="margin:0;">
                    <?php require src_path('Views/partials/csrf.php'); ?>
                    <input type="hidden" name="student_id" value="<?= e((string) $student['student_id']) ?>">
                    <button type="submit"
                            class="button secondary"
                            style="color:var(--color-danger-text,#ff6b6b); border-color:rgba(255,107,107,0.35);"
                            data-confirm="Delete your account? This will permanently remove all your data and cannot be undone.">
                        Delete Account
                    </button>
                </form>
            <?php else: ?>
                <a class="button secondary" href="<?= e(url('/re-register')) ?>">Re-Register</a>
                <a class="button" href="<?= e(url('/login')) ?>">Login to Edit</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ── Identity + Derived + Files summary ─────────────── -->
<section class="detail-grid">
    <article>
        <h3>Identity</h3>
        <?php if ($isOwner): ?>
            <div class="form-group" style="margin-bottom:0.75rem;">
                <input type="text" id="full_name" name="full_name" value="<?= e($student['full_name']) ?>" form="student-update-form" required>
                <label for="full_name">Full Name</label>
            </div>
            <p>IC Number: <?= e($student['ic_number']) ?></p>
            <div class="form-group" style="margin-bottom:0.75rem; margin-top:0.5rem;">
                <input type="tel" id="phone" name="phone" value="<?= e($student['phone']) ?>"
                       form="student-update-form" required
                       pattern="(\+?60|0)[0-9\-\s]{7,11}"
                       maxlength="16"
                       title="Malaysian phone number, e.g. 012-3456789">
                <label for="phone">Phone</label>
            </div>
            <div class="form-group" style="margin-bottom:0.75rem;">
                <input type="email" id="email" name="email" value="<?= e($student['email']) ?>" form="student-update-form" required>
                <label for="email">Email</label>
            </div>
            <p style="font-size:0.8rem; color:var(--color-muted); margin-top:-0.25rem; margin-bottom:0.75rem;">
                Email type: <span class="tag-pill"><?= e($student['email_category']) ?></span>
            </p>
        <?php else: ?>
            <p>Full Name: <strong><?= e($student['full_name']) ?></strong></p>
            <p>IC Number: <?= e($student['ic_number']) ?></p>
            <p>Phone: <?= e($student['phone']) ?></p>
            <p>Email: <?= e($student['email']) ?></p>
            <p>Email Type: <?= e($student['email_category']) ?></p>
        <?php endif; ?>
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

<?php if ($isOwner): ?>
<!-- ── Password change (edit mode only) ───────────────── -->
<section class="card">
    <h3>Change Password <span class="muted" style="font-size:0.8rem; font-weight:400;">(optional — leave blank to keep current)</span></h3>
    <div class="form-grid two-col" style="margin-top:1rem;">
        <div class="form-group">
            <input type="password" id="current_password" name="current_password" form="student-update-form" autocomplete="current-password">
            <label for="current_password">Current Password</label>
        </div>
        <div class="form-group">
            <input type="password" id="new_password" name="new_password" form="student-update-form" autocomplete="new-password">
            <label for="new_password">New Password</label>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── Uploaded files ─────────────────────────────────── -->
<?php if (empty($files)): ?>
<section class="card">
    <p class="muted">No files uploaded for this student.<?= $isOwner ? ' Use the fields below to upload files.' : '' ?></p>
</section>
<?php else: ?>
<section class="card">
    <h3 style="margin-bottom:1rem;">Uploaded Files</h3>
    <div class="metadata-grid">
    <?php foreach ($files as $file): ?>
        <?php $cbr = $file['cbr'] ?? []; $tags = $file['tags'] ?? []; ?>
        <div class="fc">

            <!-- Media preview area -->
            <div class="fc-media">
            <?php if ($file['file_type'] === 'photo'): ?>
                <img class="fc-media__img"
                     src="<?= e(url('/file?id=' . $file['id'])) ?>"
                     alt="<?= e($file['filename']) ?>"
                     loading="lazy">
            <?php elseif ($file['file_type'] === 'audio'): ?>
                <div class="fc-media__audio custom-player custom-player--audio"
                     data-src="<?= e(url('/file?id=' . $file['id'])) ?>">
                    <button class="cp-play" type="button" aria-label="Play">&#9654;</button>
                    <input class="cp-seek" type="range" value="0" min="0" max="100" step="0.01">
                    <span class="cp-current">0:00</span>
                    <button class="cp-mute" type="button" aria-label="Mute">&#128266;</button>
                    <audio preload="none"></audio>
                </div>
            <?php elseif ($file['file_type'] === 'video'): ?>
                <div class="fc-media__video custom-player custom-player--video"
                     data-src="<?= e(url('/file?id=' . $file['id'])) ?>">
                    <video preload="none"></video>
                    <div class="cp-controls">
                        <button class="cp-play" type="button" aria-label="Play">&#9654;</button>
                        <span class="cp-current">0:00</span>
                        <input class="cp-seek" type="range" value="0" min="0" max="100" step="0.01">
                        <span class="cp-duration">--:--</span>
                        <button class="cp-mute" type="button" aria-label="Mute">&#128266;</button>
                        <input class="cp-volume" type="range" value="1" min="0" max="1" step="0.05">
                        <button class="cp-fullscreen" type="button" aria-label="Fullscreen">&#x26F6;</button>
                    </div>
                </div>
            <?php else: ?>
                <div class="fc-media__icon">
                    <?php if (!empty($fileIcons[$file['file_type']])): ?>
                        <img src="<?= e($fileIcons[$file['file_type']]) ?>" alt="" aria-hidden="true">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            </div>

            <!-- Body -->
            <div class="fc-body">
                <div class="fc-header">
                    <span class="fc-name" title="<?= e($file['filename']) ?>"><?= e($file['filename']) ?></span>
                    <span class="fc-type-badge"><?= e(strtoupper($file['file_type'])) ?></span>
                </div>

                <dl class="fc-meta">
                    <div class="fc-meta__row">
                        <dt>Size</dt><dd><?= e(fmt_bytes((int) $file['file_size'])) ?></dd>
                    </div>
                    <div class="fc-meta__row">
                        <dt>Uploaded</dt><dd><?= e(substr($file['upload_date'], 0, 10)) ?></dd>
                    </div>
                    <?php if (!empty($file['original_date'])): ?>
                    <div class="fc-meta__row">
                        <dt><?= $file['file_type'] === 'photo' ? 'Captured' : 'Date' ?></dt>
                        <dd><?= e($file['original_date']) ?></dd>
                    </div>
                    <?php endif; ?>

                    <?php if ($file['file_type'] === 'audio' && $cbr !== []): ?>
                    <div class="fc-meta__row">
                        <dt>Duration</dt><dd><?= e(fmt_seconds((int) ($cbr['audio_duration_sec'] ?? 0))) ?> <span class="fc-tier"><?= e($cbr['audio_duration_tier'] ?? '') ?></span></dd>
                    </div>
                    <div class="fc-meta__row">
                        <dt>Bitrate</dt><dd><?= e((string) ($cbr['audio_bitrate'] ?? '—')) ?> kbps</dd>
                    </div>
                    <?php elseif ($file['file_type'] === 'video' && $cbr !== []): ?>
                    <div class="fc-meta__row">
                        <dt>Resolution</dt><dd><?= e($cbr['video_resolution'] ?? '—') ?> <span class="fc-tier"><?= e($cbr['video_resolution_tier'] ?? '') ?></span></dd>
                    </div>
                    <div class="fc-meta__row">
                        <dt>Duration</dt><dd><?= ($cbr['video_duration_sec'] ?? 0) > 0 ? e(fmt_seconds((int) $cbr['video_duration_sec'])) : '—' ?></dd>
                    </div>
                    <?php elseif ($file['file_type'] === 'pdf'): ?>
                        <?php $safeText = !empty($file['extracted_text']) && !str_contains((string) $file['extracted_text'], 'is not recognized') ? (string) $file['extracted_text'] : ''; ?>
                    <div class="fc-meta__row">
                        <dt>Text</dt><dd><?= $safeText !== '' ? e(mb_strimwidth($safeText, 0, 80, '…')) : 'not extracted' ?></dd>
                    </div>
                    <?php endif; ?>
                </dl>

                <?php if ($file['file_type'] === 'photo' && $cbr !== []): ?>
                <div class="fc-tags">
                    <?php if (!empty($cbr['photo_category'])): ?><span class="tag-pill"><?= e($cbr['photo_category']) ?></span><?php endif; ?>
                    <?php if (!empty($cbr['dominant_expression'])): ?>
                    <span class="tag-pill"><?= e($cbr['dominant_expression']) ?><?php if (isset($cbr['expression_confidence'])): ?> <?= e(round((float) $cbr['expression_confidence'] * 100)) ?>%<?php endif; ?></span>
                    <?php endif; ?>
                    <?php if (!empty($cbr['dominant_bg_color'])): ?>
                    <span class="tag-pill" style="display:inline-flex;align-items:center;gap:0.3rem;">
                        <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:<?= e($cbr['dominant_bg_color']) ?>;flex-shrink:0;"></span><?= e($cbr['dominant_bg_color']) ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($tags)): ?>
                <div class="fc-tags">
                    <?php foreach ($tags as $tag): ?>
                        <span class="tag-pill"><?= e($tag['tag_name']) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Owner actions -->
            <?php if ($isOwner): ?>
            <div class="fc-actions">
                <label class="fc-replace-label">
                    <span>Replace file</span>
                    <input type="file" name="<?= e($file['file_type']) ?>" form="student-update-form">
                </label>
                <form action="<?= e(url('/delete-file')) ?>" method="post">
                    <?php require src_path('Views/partials/csrf.php'); ?>
                    <input type="hidden" name="file_id" value="<?= e((string) $file['id']) ?>">
                    <button class="fc-delete-btn" type="submit"
                            data-confirm="Delete this <?= e($file['file_type']) ?> file? This cannot be undone.">
                        Delete
                    </button>
                </form>
            </div>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($isOwner): ?>
<!-- ── Upload new file types not yet uploaded ─────────── -->
<?php
$missingTypes = array_diff(['photo', 'audio', 'pdf', 'video'], array_keys($filesByType));
if (!empty($missingTypes)): ?>
<section class="card">
    <h3>Upload Missing Files</h3>
    <p class="muted" style="margin-bottom:1rem;">These file types have not been uploaded yet.</p>
    <div class="upload-grid">
        <?php foreach ($missingTypes as $type): ?>
        <div class="upload-box">
            <span class="upload-icon">
                <?php if (!empty($fileIcons[$type])): ?>
                    <img src="<?= e($fileIcons[$type]) ?>" alt="" aria-hidden="true">
                <?php else: ?>
                    <span>FILE</span>
                <?php endif; ?>
            </span>
            <p><?= ucfirst($type) ?></p>
            <input type="file" name="<?= e($type) ?>" form="student-update-form" style="margin-top:0.5rem;">
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- ── Save All Changes button ────────────────────────── -->
<section class="card" style="text-align:center;">
    <button type="submit" form="student-update-form" class="button" style="min-width:220px; font-size:1rem;">
        Save All Changes
    </button>
</section>

</form><!-- end edit form -->
<?php endif; ?>

<!-- ── Registration history ───────────────────────────── -->
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
