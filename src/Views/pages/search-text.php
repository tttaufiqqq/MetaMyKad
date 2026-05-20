<?php
/** @var array  $results */
/** @var bool   $submitted */
/** @var string $keyword */
/** @var string $tag */
?>
<section class="search-panel">
    <h2>Text-Based Retrieval</h2>
    <form class="form-grid" method="get" action="<?= e(url('/search-text')) ?>">
        <input type="hidden" name="_search" value="1">
        <div class="form-group">
            <label for="keyword">Keyword Or Phrase</label>
            <input id="keyword" name="keyword" type="text"
                   value="<?= e($keyword ?? '') ?>"
                   placeholder="Search in extracted PDF text...">
        </div>
        <div class="form-group">
            <label for="tag">Tag (optional)</label>
            <input id="tag" name="tag" type="text"
                   value="<?= e($tag ?? '') ?>"
                   placeholder="Filter by tag name">
        </div>
        <div class="form-actions">
            <button class="button" type="submit">Search PDF Text</button>
            <a class="button secondary" href="<?= e(url('/search-text')) ?>">Clear Filters</a>
        </div>
    </form>
</section>

<section class="table-card">
    <h3>Results</h3>
    <?php if (!($submitted ?? false)): ?>
        <p class="muted" style="padding:1rem;">Enter a keyword to search.</p>
    <?php elseif (($keyword ?? '') === ''): ?>
        <p class="muted" style="padding:1rem;">Enter a keyword to search.</p>
    <?php elseif (empty($results)): ?>
        <p class="muted" style="padding:1rem;">
            No matching records found for keyword: <strong><?= e($keyword ?? '') ?></strong>
        </p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Student</th>
                <th>File</th>
                <th>File Type</th>
                <th>MIME Type</th>
                <th>Tags</th>
                <th>Upload Date</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($results as $row): ?>
            <tr>
                <td><?= e($row['full_name']) ?></td>
                <td><?= e($row['filename']) ?></td>
                <td><?= e($row['file_type']) ?></td>
                <td><?= e($row['mime_type']) ?></td>
                <td><?= e($row['tag_list'] ?? '—') ?></td>
                <td><?= e($row['upload_date']) ?></td>
                <td>
                    <a class="button" href="<?= e(url('/student-detail?id=' . $row['student_id'])) ?>">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
