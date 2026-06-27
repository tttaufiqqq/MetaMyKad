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

        $safeText = !empty($file['extracted_text']) && !str_contains((string) $file['extracted_text'], 'is not recognized')
            ? (string) $file['extracted_text'] : null;

        $cbrData = [];
        if ($cbr !== []) {
            if ($file['file_type'] === 'audio') {
                $cbrData = [
                    'duration'      => fmt_seconds((int) ($cbr['audio_duration_sec'] ?? 0)),
                    'duration_tier' => $cbr['audio_duration_tier'] ?? null,
                    'bitrate'       => $cbr['audio_bitrate'] ?? null,
                    'sample_rate'   => $cbr['audio_sample_rate'] ?? null,
                    'channels'      => $cbr['audio_channels'] ?? null,
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
            'id'              => $file['id'],
            'type'            => $file['file_type'],
            'filename'        => $file['filename'],
            'url'             => url('/file?id=' . $file['id']),
            'size'            => fmt_bytes((int) $file['file_size']),
            'uploaded'        => substr($file['upload_date'], 0, 10),
            'original_date'   => $file['original_date'] ?? null,
            'mime'            => $file['mime_type'] ?? null,
            'text'            => $safeText,
            'cbr'             => $cbrData,
            'tags'            => array_values(array_map(fn($t) => $t['tag_name'], $tags)),
            'is_owner'        => $isOwner,
            'file_type_label' => $file['file_type'],
        ]);
        ?>
        <div class="fc" data-fc="<?= htmlspecialchars($fcJson, ENT_QUOTES, 'UTF-8') ?>">

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
                    $videoRes  = $cbr !== [] ? ($cbr['video_resolution'] ?? '—') : '—';
                    $videoTier = ($cbr !== [] && !empty($cbr['video_resolution_tier'])) ? $cbr['video_resolution_tier'] : null;
                    $videoDur  = ($cbr !== [] && ($cbr['video_duration_sec'] ?? 0) > 0) ? fmt_seconds((int) $cbr['video_duration_sec']) : '—';
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
                    $fmtExpression = isset($cbr['dominant_expression']) ? ucfirst((string) $cbr['dominant_expression']) : '—';
                    $fmtConfidence = isset($cbr['expression_confidence']) ? ' ' . round((float) $cbr['expression_confidence'] * 100) . '%' : '';
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
