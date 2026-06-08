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
            <td>
                <?php if ($row['action'] === 'new'): ?>
                    <span style="display:inline-flex;align-items:center;gap:0.35rem;color:#4ade80;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        new
                    </span>
                <?php else: ?>
                    <span style="display:inline-flex;align-items:center;gap:0.35rem;color:#38bdf8;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        update
                    </span>
                <?php endif; ?>
            </td>
            <td><?= fmt_date($row['registered_at']) ?></td>
            <td><?= e((string) $row['files_uploaded']) ?></td>
            <td><?= badge_icon((string) $row['badge_at_time']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</section>
