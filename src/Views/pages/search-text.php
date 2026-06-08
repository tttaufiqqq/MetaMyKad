<?php
/** @var array  $results */
/** @var bool   $submitted */
/** @var string $keyword */
/** @var string $tag */
?>
<section class="search-panel">
    <h2>Text-Based Retrieval</h2>
    <p class="muted">
        Search across all file types by <strong>tag</strong> (photo, audio, PDF, video),
        or search within <strong>extracted text</strong> content using a keyword.
        You can use either or both fields.
    </p>
    <form class="form-grid" method="get" action="<?= e(url('/search-text')) ?>">
        <input type="hidden" name="_search" value="1">
        <div class="form-group">
            <input id="keyword" name="keyword" type="text"
                   value="<?= e($keyword ?? '') ?>"
                   placeholder="e.g. resume, project, report...">
            <label for="keyword">Keyword (searches extracted text)</label>
        </div>
        <div class="form-group">
            <input id="tag" name="tag" type="text"
                   value="<?= e($tag ?? '') ?>"
                   placeholder="e.g. formal, audio, long, neutral...">
            <label for="tag">Tag (searches all file types)</label>
        </div>
        <div class="form-actions">
            <button class="button" type="submit">Search</button>
            <a class="button secondary" href="<?= e(url('/search-text')) ?>">Clear</a>
        </div>
    </form>
</section>

<section class="table-card">
    <h3>Results</h3>
    <?php if (!($submitted ?? false)): ?>
        <p class="muted">Enter a keyword or tag to search.</p>
    <?php elseif (($keyword ?? '') === '' && ($tag ?? '') === ''): ?>
        <p class="muted">Enter a keyword or tag to search.</p>
    <?php elseif (empty($results)): ?>
        <p class="muted">
            No results found
            <?php if (($keyword ?? '') !== ''): ?>for keyword <strong><?= e($keyword) ?></strong><?php endif; ?>
            <?php if (($tag ?? '') !== ''): ?>with tag <strong><?= e($tag) ?></strong><?php endif; ?>.
        </p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Student</th>
                <th>File</th>
                <th>Type</th>
                <th>Tags</th>
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
                <td><?= e($row['filename']) ?></td>
                <td><span class="fc-type-badge"><?= e(strtoupper($row['file_type'])) ?></span></td>
                <td>
                    <?php if (!empty($row['tag_list'])): ?>
                        <?php foreach (explode(',', $row['tag_list']) as $t): ?>
                            <span class="tag-pill"><?= e(trim($t)) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>—<?php endif; ?>
                </td>
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
