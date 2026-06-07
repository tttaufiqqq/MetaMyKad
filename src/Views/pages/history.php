<?php
/** @var array $rows */
?>
<section class="card">
    <h2>Registration History (<?= count($rows) ?> entries)</h2>
    <p class="muted">All registration and re-registration events, newest first. Showing up to 50 entries.</p>
</section>

<?php if (empty($rows)): ?>
    <div class="card">
        <h3>No History Log Found</h3>
        <p class="text-dim">There are no recent registration activities recorded in the system yet.</p>
    </div>
<?php else: ?>
    <div class="table-card">
        <h3>History Log</h3>
        <table>
            <thead>
            <tr>
                <th>Full Name</th>
                <th>IC Number</th>
                <th>Action</th>
                <th>Registered At</th>
                <th>Files Uploaded</th>
                <th>Badge At Time</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e($row['full_name'] ?? '—') ?></td>
                <td><?= e($row['ic_number']) ?></td>
                <td><span class="tag-pill"><?= e($row['action']) ?></span></td>
                <td class="text-dim small"><?= e($row['registered_at']) ?></td>
                <td><?= e((string) $row['files_uploaded']) ?></td>
                <td><?= e($row['badge_at_time']) ?></td>
                <td>
                    <a href="/search-attribute?_search=1&ic_number=<?= urlencode($row['ic_number']) ?>" class="button" style="padding: 0.25rem 0.5rem; font-size: 0.85rem;">
                        View
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>