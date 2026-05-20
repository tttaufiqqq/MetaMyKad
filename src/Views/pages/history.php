<?php
/** @var array $rows */
?>
<section class="card">
    <h2>Registration History</h2>
    <p class="muted">All registration and re-registration events, newest first. Showing up to 50 entries.</p>
</section>

<section class="table-card">
    <h3>History Log</h3>
    <?php if (empty($rows)): ?>
        <p class="muted">No history records found.</p>
    <?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Full Name</th>
            <th>IC Number</th>
            <th>Action</th>
            <th>Registered At</th>
            <th>Files Uploaded</th>
            <th>Badge At Time</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
        <tr data-student-row
            data-name="<?= e($row['full_name'] ?? '') ?>"
            data-ic="<?= e($row['ic_number']) ?>"
            data-badge="<?= e($row['badge_at_time']) ?>"
            data-href="<?= $row['student_id'] ? e(url('/student-detail?id=' . $row['student_id'])) : '' ?>">
            <td><?= e($row['full_name'] ?? '—') ?></td>
            <td><?= e($row['ic_number']) ?></td>
            <td><?= e($row['action']) ?></td>
            <td><?= e($row['registered_at']) ?></td>
            <td><?= e((string) $row['files_uploaded']) ?></td>
            <td><?= badge_icon((string) $row['badge_at_time']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</section>
