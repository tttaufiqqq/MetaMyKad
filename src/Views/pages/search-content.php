<?php
/** @var array  $results */
/** @var array  $activeFilters */
/** @var bool   $submitted */
/** @var string $photoCategory */
/** @var string $dominantExpression */
/** @var string $audioDurationTier */
/** @var string $videoResolutionTier */
?>
<section class="search-panel">
    <h2>Content-Based Retrieval</h2>
    <p class="muted" style="margin-bottom:0.75rem;">
        Search files by their analysed multimedia content — photo category and facial expression,
        audio duration tier, or video resolution tier. These values are extracted automatically
        when files are uploaded. Select one or more filters to find matching files.
    </p>
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:0.5rem; margin-bottom:1.25rem;">
        <div style="background:rgba(57,197,255,0.06); border:1px solid rgba(57,197,255,0.15); border-radius:8px; padding:0.55rem 0.75rem;">
            <p style="font-size:0.62rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; color:#39c5ff; margin:0 0 0.25rem;">Photo</p>
            <p style="font-size:0.72rem; color:var(--color-muted); margin:0; line-height:1.4;">Category (formal / non-formal) and dominant facial expression detected in the image.</p>
        </div>
        <div style="background:rgba(113,239,192,0.06); border:1px solid rgba(113,239,192,0.15); border-radius:8px; padding:0.55rem 0.75rem;">
            <p style="font-size:0.62rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; color:#71efc0; margin:0 0 0.25rem;">Audio</p>
            <p style="font-size:0.72rem; color:var(--color-muted); margin:0; line-height:1.4;">Duration tier — Short (&lt;30s), Medium (30s–2m), or Long (&gt;2m).</p>
        </div>
        <div style="background:rgba(192,132,252,0.06); border:1px solid rgba(192,132,252,0.15); border-radius:8px; padding:0.55rem 0.75rem;">
            <p style="font-size:0.62rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; color:#c084fc; margin:0 0 0.25rem;">Video</p>
            <p style="font-size:0.72rem; color:var(--color-muted); margin:0; line-height:1.4;">Resolution tier — SD (≤480p), HD (720p), FHD (1080p), or UHD (4K).</p>
        </div>
    </div>
    <form class="form-grid two-col" method="get" action="<?= e(url('/search-content')) ?>">
        <input type="hidden" name="_search" value="1">
        <div class="form-group">
            <select id="photo_category" name="photo_category">
                <option value="">Any</option>
                <option value="formal"     <?= ($photoCategory ?? '') === 'formal'     ? 'selected' : '' ?>>Formal</option>
                <option value="non_formal" <?= ($photoCategory ?? '') === 'non_formal' ? 'selected' : '' ?>>Non-formal</option>
            </select>
            <label for="photo_category">Photo Category</label>
        </div>
        <div class="form-group">
            <select id="dominant_expression" name="dominant_expression">
                <option value="">Any</option>
                <option value="happy"     <?= ($dominantExpression ?? '') === 'happy'     ? 'selected' : '' ?>>Happy</option>
                <option value="neutral"   <?= ($dominantExpression ?? '') === 'neutral'   ? 'selected' : '' ?>>Neutral</option>
                <option value="sad"       <?= ($dominantExpression ?? '') === 'sad'       ? 'selected' : '' ?>>Sad</option>
                <option value="angry"     <?= ($dominantExpression ?? '') === 'angry'     ? 'selected' : '' ?>>Angry</option>
                <option value="surprised" <?= ($dominantExpression ?? '') === 'surprised' ? 'selected' : '' ?>>Surprised</option>
                <option value="fearful"   <?= ($dominantExpression ?? '') === 'fearful'   ? 'selected' : '' ?>>Fearful</option>
                <option value="disgusted" <?= ($dominantExpression ?? '') === 'disgusted' ? 'selected' : '' ?>>Disgusted</option>
            </select>
            <label for="dominant_expression">Dominant Expression</label>
        </div>
        <div class="form-group">
            <select id="audio_duration_tier" name="audio_duration_tier">
                <option value="">Any</option>
                <option value="short"  <?= ($audioDurationTier ?? '') === 'short'  ? 'selected' : '' ?>>Short</option>
                <option value="medium" <?= ($audioDurationTier ?? '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                <option value="long"   <?= ($audioDurationTier ?? '') === 'long'   ? 'selected' : '' ?>>Long</option>
            </select>
            <label for="audio_duration_tier">Audio Duration Tier</label>
        </div>
        <div class="form-group">
            <select id="video_resolution_tier" name="video_resolution_tier">
                <option value="">Any</option>
                <option value="SD"  <?= ($videoResolutionTier ?? '') === 'SD'  ? 'selected' : '' ?>>SD</option>
                <option value="HD"  <?= ($videoResolutionTier ?? '') === 'HD'  ? 'selected' : '' ?>>HD</option>
                <option value="FHD" <?= ($videoResolutionTier ?? '') === 'FHD' ? 'selected' : '' ?>>FHD</option>
                <option value="UHD" <?= ($videoResolutionTier ?? '') === 'UHD' ? 'selected' : '' ?>>UHD</option>
            </select>
            <label for="video_resolution_tier">Video Resolution Tier</label>
        </div>
        <div class="form-actions full-span">
            <button class="button" type="submit">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Search Content
            </button>
            <a class="button secondary" href="<?= e(url('/search-content')) ?>">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Clear Filters
            </a>
        </div>
    </form>
</section>

<?php if (!empty($activeFilters)): ?>
<div class="card filter-summary">
    <p class="text-dim" style="font-size:0.78rem;">
        Active filters:
        <strong><?= e(implode(', ', array_map(fn($k, $v) => "$k = $v", array_keys($activeFilters), array_values($activeFilters)))) ?></strong>
    </p>
</div>
<?php endif; ?>

<section class="table-card">
    <h3>Results</h3>
    <?php if (!($submitted ?? false)): ?>
        <p class="muted">Select at least one content filter to search.</p>
    <?php elseif (empty($activeFilters)): ?>
        <p class="muted">Select at least one content filter to search.</p>
    <?php elseif (empty($results)): ?>
        <p class="muted">No matching records found.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Student</th>
                <th>File Type</th>
                <th>Photo Category</th>
                <th>Expression</th>
                <th>Audio Tier</th>
                <th>Video Tier</th>
                <th>Upload Date</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($results as $row): ?>
            <tr data-student-row
                data-name="<?= e($row['full_name']) ?>"
                data-file-type="<?= e($row['file_type']) ?>"
                data-href="<?= e(url('/student-detail?id=' . $row['student_id'])) ?>">
                <td><?= e($row['full_name']) ?></td>
                <td><?= e($row['file_type']) ?></td>
                <td><?= e($row['photo_category'] ?? '—') ?></td>
                <td><?= e($row['dominant_expression'] ?? '—') ?></td>
                <td><?= e($row['audio_duration_tier'] ?? '—') ?></td>
                <td><?= e($row['video_resolution_tier'] ?? '—') ?></td>
                <td><?= fmt_date($row['upload_date']) ?></td>
                <td>
                    <a class="button" href="<?= e(url('/student-detail?id=' . $row['student_id'])) ?>">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
