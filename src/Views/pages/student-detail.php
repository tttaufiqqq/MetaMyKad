<?php
/** @var array $student */
/** @var array $files */
/** @var array $history */

use MetaMyKad\Core\Auth;

$isOwner = Auth::check() && (int) Auth::user()['id'] === (int) $student['student_id'];

$fileIcons = [
    'photo' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
    'audio' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
    'pdf'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
    'video' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>',
];

$fileAccept = [
    'photo' => 'image/jpeg,image/png,.jpg,.jpeg,.png',
    'audio' => 'audio/mpeg,audio/wav,.mp3,.wav',
    'pdf'   => 'application/pdf,.pdf',
    'video' => 'video/mp4,video/quicktime,video/x-msvideo,.mp4,.mov,.avi',
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
<form id="student-update-form" action="<?= e(url('/student-update')) ?>" method="post" enctype="multipart/form-data" data-validate>
    <?php require src_path('Views/partials/csrf.php'); ?>
    <input type="hidden" name="student_id" value="<?= e((string) $student['student_id']) ?>">
<?php endif; ?>

<!-- ── Header card ─────────────────────────────────────── -->
<section class="card">
    <div class="card-header-row">
        <div>
            <h2 class="detail-name-heading"><?= e($student['full_name']) ?></h2>
            <p class="muted">Badge: <strong><?= badge_icon($student['stored_badge']) ?></strong></p>
        </div>
        <div class="owner-actions">
            <?php if ($isOwner): ?>
                <button type="submit" form="logout-form" class="button secondary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Sign Out
                </button>
                <button type="submit" form="delete-account-form"
                        class="button secondary button--danger"
                        data-confirm="Delete your account? This will permanently remove all your data and cannot be undone.">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    Delete Account
                </button>
            <?php else: ?>
                <a class="button" href="<?= e(url('/login')) ?>">Login to Edit</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ── Identity + Derived + Files summary ─────────────── -->
<section class="detail-grid">
    <article>
        <h3>Identity</h3>
        <dl class="edit-field-list">
            <div class="edit-field-row">
                <dt>Full Name</dt>
                <dd><?php if ($isOwner): ?><input type="text" name="full_name" value="<?= e($student['full_name']) ?>" class="inline-input" form="student-update-form" required><?php else: ?><strong><?= e(mb_strtoupper($student['full_name'])) ?></strong><?php endif; ?></dd>
            </div>
            <div class="edit-field-row">
                <dt>IC Number</dt>
                <dd><?= $isOwner ? e($student['ic_number']) : e($student['ic_masked']) ?></dd>
            </div>
            <div class="edit-field-row">
                <dt>Phone</dt>
                <dd><?php if ($isOwner): ?><input type="tel" name="phone" value="<?= e($student['phone']) ?>" class="inline-input" form="student-update-form" required pattern="(\+?60|0)[0-9\-\s]{7,11}" maxlength="16" title="Malaysian phone number, e.g. 012-3456789"><?php else: ?><?= e($student['phone']) ?><?php endif; ?></dd>
            </div>
            <div class="edit-field-row">
                <dt>Email</dt>
                <dd><?php if ($isOwner): ?><input type="email" name="email" value="<?= e($student['email']) ?>" class="inline-input" form="student-update-form" required><?php else: ?><?= e($student['email']) ?><?php endif; ?></dd>
            </div>
            <div class="edit-field-row">
                <dt>Email Type</dt>
                <dd><span class="tag-pill"><?= e($student['email_category']) ?></span></dd>
            </div>
        </dl>
    </article>
    <article>
        <h3>Derived Attributes</h3>
        <p>Date of Birth: <?= fmt_date($student['date_of_birth']) ?></p>
        <p>Age: <?= e((string) $student['current_age']) ?> years</p>
        <p>Gender: <?= $student['gender'] === 'M' ? 'Male' : 'Female' ?></p>
        <p>State of Birth: <?= e($student['state_of_birth']) ?></p>
        <p>Badge: <strong><?= badge_icon($student['stored_badge']) ?></strong></p>
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
    <h3>Change Password <span class="field-note">(optional — leave blank to keep current)</span></h3>
    <dl class="edit-field-list" style="margin-top:0.75rem;">
        <div class="edit-field-row">
            <dt>Current</dt>
            <dd><input type="password" name="current_password" class="inline-input" form="student-update-form" autocomplete="current-password" placeholder="Enter current password"></dd>
        </div>
        <div class="edit-field-row">
            <dt>New</dt>
            <dd><input type="password" name="new_password" class="inline-input" form="student-update-form" autocomplete="new-password" placeholder="Enter new password"></dd>
        </div>
    </dl>
</section>
<?php endif; ?>

<!-- ── Uploaded files ─────────────────────────────────── -->
<?php if (empty($files)): ?>
<section class="card">
    <p class="muted">No files uploaded for this student.<?= $isOwner ? ' Use the fields below to upload files.' : '' ?></p>
</section>
<?php else: ?>
<section class="card">
    <h3>Uploaded Files</h3>
    <div class="metadata-grid">
    <?php foreach ($files as $file): ?>
        <?php
        $cbr  = $file['cbr']  ?? [];
        $tags = $file['tags'] ?? [];

        // Build JSON payload for the modal
        $safeText = !empty($file['extracted_text']) && !str_contains((string) $file['extracted_text'], 'is not recognized')
            ? (string) $file['extracted_text'] : null;

        $cbrData = [];
        if ($cbr !== []) {
            if ($file['file_type'] === 'audio') {
                $cbrData = [
                    'duration'       => fmt_seconds((int) ($cbr['audio_duration_sec'] ?? 0)),
                    'duration_tier'  => $cbr['audio_duration_tier'] ?? null,
                    'bitrate'        => $cbr['audio_bitrate'] ?? null,
                    'sample_rate'    => $cbr['audio_sample_rate'] ?? null,
                    'channels'       => $cbr['audio_channels'] ?? null,
                ];
            } elseif ($file['file_type'] === 'video') {
                $cbrData = [
                    'resolution'      => $cbr['video_resolution'] ?? null,
                    'resolution_tier' => $cbr['video_resolution_tier'] ?? null,
                    'duration'        => ($cbr['video_duration_sec'] ?? 0) > 0 ? fmt_seconds((int) $cbr['video_duration_sec']) : null,
                    'framerate'       => isset($cbr['video_framerate']) ? $cbr['video_framerate'] . ' fps' : null,
                    'codec'           => $cbr['video_codec'] ?? null,
                ];
            } elseif ($file['file_type'] === 'photo') {
                $cbrData = [
                    'category'              => match ($cbr['photo_category'] ?? null) {
                        'formal'     => 'Formal',
                        'non_formal' => 'Not Formal',
                        default      => null,
                    },
                    'expression'            => isset($cbr['dominant_expression']) ? ucfirst((string) $cbr['dominant_expression']) : null,
                    'expression_confidence' => isset($cbr['expression_confidence']) ? round((float) $cbr['expression_confidence'] * 100) : null,
                    'bg_color'              => $cbr['dominant_bg_color'] ?? null,
                    'width'                 => $cbr['photo_width'] ?? null,
                    'height'                => $cbr['photo_height'] ?? null,
                ];
            }
        }

        $fcJson = json_encode([
            'id'            => $file['id'],
            'type'          => $file['file_type'],
            'filename'      => $file['filename'],
            'url'           => url('/file?id=' . $file['id']),
            'size'          => fmt_bytes((int) $file['file_size']),
            'uploaded'      => substr($file['upload_date'], 0, 10),
            'original_date' => $file['original_date'] ?? null,
            'mime'          => $file['mime_type'] ?? null,
            'text'          => $safeText,
            'cbr'           => $cbrData,
            'tags'          => array_values(array_map(fn($t) => $t['tag_name'], $tags)),
            'is_owner'      => $isOwner,
            'file_type_label' => $file['file_type'],
        ]);
        ?>
        <div class="fc" data-fc="<?= htmlspecialchars($fcJson, ENT_QUOTES, 'UTF-8') ?>">

            <!-- Thumbnail (left) -->
            <div class="fc-media">
            <?php if ($file['file_type'] === 'photo'): ?>
                <img class="fc-media__img"
                     src="<?= e(url('/file?id=' . $file['id'])) ?>"
                     alt="<?= e($file['filename']) ?>"
                     loading="lazy">
            <?php else: ?>
                <div class="fc-media__icon">
                    <?php if (!empty($fileIcons[$file['file_type']])): ?>
                        <?= $fileIcons[$file['file_type']] ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            </div>

            <!-- Summary (middle) -->
            <div class="fc-body">
                <div class="fc-header">
                    <span class="fc-name" title="<?= e($file['filename']) ?>"><?= e($file['filename']) ?></span>
                    <span class="fc-type-badge"><?= e(strtoupper($file['file_type'])) ?></span>
                </div>
                <dl class="fc-meta">
                    <div class="fc-meta__row"><dt>Size</dt><dd><?= e(fmt_bytes((int) $file['file_size'])) ?></dd></div>
                    <div class="fc-meta__row"><dt>Uploaded</dt><dd><?= fmt_date($file['upload_date']) ?></dd></div>
                    <?php if (!empty($file['original_date'])): ?>
                    <div class="fc-meta__row"><dt>Created</dt><dd><?= fmt_date($file['original_date']) ?></dd></div>
                    <?php endif; ?>
                    <?php if ($file['file_type'] === 'audio'): ?>
                    <?php
                    $audioDur  = $cbr !== [] ? fmt_seconds((int) ($cbr['audio_duration_sec'] ?? 0)) : '—';
                    $audioTier = ($cbr !== [] && !empty($cbr['audio_duration_tier'])) ? $cbr['audio_duration_tier'] : null;
                    $audioBr   = $cbr !== [] ? ((string) ($cbr['audio_bitrate'] ?? '—')) . ' kbps' : '—';
                    ?>
                    <div class="fc-meta__row"><dt>Duration</dt><dd><?= e($audioDur) ?><?= $audioTier ? ' <span class="fc-tier">' . e($audioTier) . '</span>' : '' ?></dd></div>
                    <div class="fc-meta__row"><dt>Bitrate</dt><dd><?= e($audioBr) ?></dd></div>
                    <?php elseif ($file['file_type'] === 'video'): ?>
                    <?php
                    $videoRes    = $cbr !== [] ? ($cbr['video_resolution'] ?? '—') : '—';
                    $videoTier   = ($cbr !== [] && !empty($cbr['video_resolution_tier'])) ? $cbr['video_resolution_tier'] : null;
                    $videoDur    = ($cbr !== [] && ($cbr['video_duration_sec'] ?? 0) > 0) ? fmt_seconds((int) $cbr['video_duration_sec']) : '—';
                    ?>
                    <div class="fc-meta__row"><dt>Resolution</dt><dd><?= e($videoRes) ?><?= $videoTier ? ' <span class="fc-tier">' . e($videoTier) . '</span>' : '' ?></dd></div>
                    <div class="fc-meta__row"><dt>Duration</dt><dd><?= e($videoDur) ?></dd></div>
                    <?php elseif ($file['file_type'] === 'pdf'): ?>
                    <div class="fc-meta__row"><dt>Text</dt><dd><?= $safeText !== null ? e(mb_strimwidth($safeText, 0, 60, '…')) : 'not extracted' ?></dd></div>
                    <?php elseif ($file['file_type'] === 'photo'): ?>
                    <?php
                    $fmtCategory = match ($cbr['photo_category'] ?? null) {
                        'formal'     => 'Formal',
                        'non_formal' => 'Not Formal',
                        default      => '—',
                    };
                    $fmtExpression = isset($cbr['dominant_expression'])
                        ? ucfirst((string) $cbr['dominant_expression'])
                        : '—';
                    $fmtConfidence = isset($cbr['expression_confidence'])
                        ? ' ' . round((float) $cbr['expression_confidence'] * 100) . '%'
                        : '';
                    ?>
                    <div class="fc-meta__row"><dt>Category</dt><dd><?= e($fmtCategory) ?></dd></div>
                    <div class="fc-meta__row"><dt>Expression</dt><dd><?= e($fmtExpression . $fmtConfidence) ?></dd></div>
                    <?php endif; ?>
                </dl>
                <?php if (!empty($tags) || $isOwner): ?>
                <div class="fc-tag-manager"
                     data-file-id="<?= e((string) $file['id']) ?>"
                     data-add-url="<?= e(url('/tag-add')) ?>"
                     data-remove-url="<?= e(url('/tag-remove')) ?>">
                    <?php if (!empty($tags)): ?>
                    <div class="fc-tags">
                        <?php foreach ($tags as $tag): ?>
                        <span class="tag-pill">
                            <?= e($tag['tag_name']) ?>
                            <?php if ($isOwner): ?>
                            <button type="button" class="tag-remove-btn"
                                    data-tag="<?= e($tag['tag_name']) ?>"
                                    aria-label="Remove tag <?= e($tag['tag_name']) ?>">&times;</button>
                            <?php endif; ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($isOwner): ?>
                    <div class="tag-add-row">
                        <input type="text" class="tag-add-input"
                               placeholder="Add tag…" maxlength="32" autocomplete="off">
                        <button type="button" class="tag-add-btn">+ Add</button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Owner actions (right column) -->
            <?php if ($isOwner): ?>
            <div class="fc-actions">
                <label class="fc-replace-label">
                    <span>Replace file</span>
                    <input type="file" name="<?= e($file['file_type']) ?>" form="student-update-form"
                           accept="<?= e($fileAccept[$file['file_type']] ?? '') ?>">
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

<!-- ── File detail modal ───────────────────────────────── -->
<div id="fc-modal" class="fm-overlay" hidden aria-modal="true" role="dialog" aria-label="File detail">
    <div class="fm">
        <button id="fc-modal-close" class="fm-close" type="button" aria-label="Close">&times;</button>
        <div class="fm-media" id="fm-media"></div>
        <div class="fm-body">
            <div class="fm-header">
                <span class="fm-name" id="fm-name"></span>
                <span class="fc-type-badge" id="fm-badge"></span>
            </div>
            <dl class="fc-meta fm-meta" id="fm-meta"></dl>
            <div class="fm-tags fc-tags" id="fm-tags"></div>
        </div>
    </div>
</div>

<script>
(function () {
    var overlay  = document.getElementById('fc-modal');
    var mediaEl  = document.getElementById('fm-media');
    var nameEl   = document.getElementById('fm-name');
    var badgeEl  = document.getElementById('fm-badge');
    var metaEl   = document.getElementById('fm-meta');
    var tagsEl   = document.getElementById('fm-tags');

    function esc(str) {
        var d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function addRow(label, valueHtml) {
        var div = document.createElement('div');
        div.className = 'fc-meta__row';
        div.innerHTML = '<dt>' + esc(label) + '</dt><dd>' + valueHtml + '</dd>';
        metaEl.appendChild(div);
    }

    function openModal(card) {
        var raw = card.getAttribute('data-fc');
        if (!raw) return;
        var f;
        try { f = JSON.parse(raw); } catch (e) { return; }

        // Media
        mediaEl.innerHTML = '';
        if (f.type === 'photo') {
            var img = document.createElement('img');
            img.src = f.url;
            img.alt = f.filename;
            mediaEl.appendChild(img);
        } else if (f.type === 'audio') {
            var audio = document.createElement('audio');
            audio.controls = true;
            audio.src = f.url;
            mediaEl.appendChild(audio);
        } else if (f.type === 'video') {
            var video = document.createElement('video');
            video.controls = true;
            video.src = f.url;
            mediaEl.appendChild(video);
        } else if (f.type === 'pdf') {
            var iframe = document.createElement('iframe');
            iframe.src = f.url;
            iframe.title = f.filename;
            mediaEl.appendChild(iframe);
        }

        // Header
        nameEl.textContent = f.filename;
        badgeEl.textContent = f.type.toUpperCase();

        // Meta
        metaEl.innerHTML = '';
        addRow('SIZE', esc(f.size));
        addRow('UPLOADED', esc(f.uploaded));
        if (f.original_date) addRow('CREATED', esc(f.original_date));
        if (f.mime) addRow('MIME', esc(f.mime));

        var c = f.cbr || {};
        if (f.type === 'audio') {
            if (c.duration) addRow('DURATION', esc(c.duration) + (c.duration_tier ? ' <span class="fc-tier">' + esc(c.duration_tier) + '</span>' : ''));
            if (c.bitrate)  addRow('BITRATE', esc(String(c.bitrate)) + ' kbps');
            if (c.sample_rate) addRow('SAMPLE RATE', esc(String(c.sample_rate)) + ' Hz');
            if (c.channels) addRow('CHANNELS', esc(String(c.channels)));
        } else if (f.type === 'video') {
            if (c.resolution) addRow('RESOLUTION', esc(c.resolution) + (c.resolution_tier ? ' <span class="fc-tier">' + esc(c.resolution_tier) + '</span>' : ''));
            if (c.duration)   addRow('DURATION', esc(c.duration));
            if (c.framerate)  addRow('FRAME RATE', esc(c.framerate));
            if (c.codec)      addRow('CODEC', esc(c.codec));
        } else if (f.type === 'photo') {
            if (c.category)   addRow('CATEGORY', esc(c.category));
            if (c.expression) addRow('EXPRESSION', esc(c.expression) + (c.expression_confidence ? ' <span class="fc-tier">' + c.expression_confidence + '%</span>' : ''));
            if (c.bg_color)   addRow('BG COLOR', '<span style="display:inline-flex;align-items:center;gap:0.35rem;"><span style="width:10px;height:10px;border-radius:50%;background:' + esc(c.bg_color) + ';display:inline-block;flex-shrink:0;"></span>' + esc(c.bg_color) + '</span>');
            if (c.width && c.height) addRow('DIMENSIONS', esc(c.width) + ' × ' + esc(c.height) + ' px');
        } else if (f.type === 'pdf' && f.text) {
            addRow('EXTRACTED TEXT', '<span style="white-space:pre-wrap;word-break:break-word;">' + esc(f.text.slice(0, 400)) + (f.text.length > 400 ? '…' : '') + '</span>');
        }

        // Tags
        tagsEl.innerHTML = '';
        (f.tags || []).forEach(function (tag) {
            var span = document.createElement('span');
            span.className = 'tag-pill';
            span.textContent = tag;
            tagsEl.appendChild(span);
        });

        overlay.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        overlay.hidden = true;
        document.body.style.overflow = '';
        mediaEl.innerHTML = '';
    }

    // Open on card click (skip actions panel and tag manager)
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.fc-actions') && !e.target.closest('.fc-tag-manager')) {
            var card = e.target.closest('.fc[data-fc]');
            if (card) openModal(card);
        }
    });

    // Close on backdrop click
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeModal();
    });

    document.getElementById('fc-modal-close').addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !overlay.hidden) closeModal();
    });
}());
</script>
<?php if ($isOwner): ?>
<script>
(function () {
    var FILE_TYPES = ['photo', 'audio', 'pdf', 'video'];
    var formEl = document.getElementById('student-update-form');

    function setHidden(form, name, value) {
        var h = form.querySelector('[name="' + name + '"]');
        if (!h) {
            h = document.createElement('input');
            h.type = 'hidden';
            h.name = name;
            form.appendChild(h);
        }
        h.value = value;
    }

    document.addEventListener('change', function (e) {
        var input = e.target;
        if (input.type !== 'file') return;
        var type = input.name;
        if (FILE_TYPES.indexOf(type) === -1) return;

        var f = formEl || input.form;
        if (!f) return;

        // Capture original file date
        if (input.files && input.files[0]) {
            var d = new Date(input.files[0].lastModified);
            setHidden(f, 'original_date_' + type,
                d.getFullYear() + '-' +
                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                String(d.getDate()).padStart(2, '0'));
        }

        // Capture media duration for audio/video
        if ((type === 'audio' || type === 'video') && input.files && input.files[0]) {
            var url = URL.createObjectURL(input.files[0]);
            var el = document.createElement(type === 'video' ? 'video' : 'audio');
            el.preload = 'metadata';
            el.onloadedmetadata = function () {
                URL.revokeObjectURL(url);
                setHidden(f, 'duration_sec_' + type, String(Math.round(el.duration)));
            };
            el.src = url;
        }
    });
}());
</script>
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
                    <?= $fileIcons[$type] ?>
                <?php else: ?>
                    <span>FILE</span>
                <?php endif; ?>
            </span>
            <p><?= ucfirst($type) ?></p>
            <input type="file" name="<?= e($type) ?>" form="student-update-form"
                   accept="<?= e($fileAccept[$type] ?? '') ?>" style="margin-top:0.5rem;">
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
<?php if ($isOwner): ?>
<form id="logout-form" action="<?= e(url('/logout')) ?>" method="post" style="display:none;">
    <?php require src_path('Views/partials/csrf.php'); ?>
</form>
<form id="delete-account-form" action="<?= e(url('/delete-account')) ?>" method="post" style="display:none;">
    <?php require src_path('Views/partials/csrf.php'); ?>
    <input type="hidden" name="student_id" value="<?= e((string) $student['student_id']) ?>">
</form>
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
            <td><?= fmt_date($entry['registered_at']) ?></td>
            <td><?= e((string) $entry['files_uploaded']) ?></td>
            <td><?= badge_icon((string) $entry['badge_at_time']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>
