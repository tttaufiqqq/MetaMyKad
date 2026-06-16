<?php
/** @var int   $totalStudents */
/** @var array $fileCounts */
/** @var array $badgeRows */
/** @var array $recentRows */
?>
<section class="card">
    <h2>Staff Dashboard</h2>
    <p class="muted">System overview, badge distribution, and recent registry activity.</p>
</section>

<section class="metric-grid">
    <article>
        <span class="metric-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="7" r="3"/><path d="M3 21v-2a5 5 0 0 1 5-5h2"/><circle cx="17" cy="11" r="3"/><path d="M14.5 21v-1.5a3.5 3.5 0 0 1 3.5-3.5h0a3.5 3.5 0 0 1 3.5 3.5V21"/></svg></span>
        <h3 class="mb-2">Total Students</h3>
        <p class="metric-value"><?= e((string) $totalStudents) ?></p>
        <p class="text-dim">Registered in the system</p>
    </article>
    <article>
        <span class="metric-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></span>
        <h3 class="mb-2">Photos</h3>
        <p class="metric-value"><?= e((string) $fileCounts['photo']) ?></p>
        <p class="text-dim">Photo files stored</p>
    </article>
    <article>
        <span class="metric-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg></span>
        <h3 class="mb-2">Audio</h3>
        <p class="metric-value"><?= e((string) $fileCounts['audio']) ?></p>
        <p class="text-dim">Audio files stored</p>
    </article>
    <article>
        <span class="metric-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
        <h3 class="mb-2">PDFs</h3>
        <p class="metric-value"><?= e((string) $fileCounts['pdf']) ?></p>
        <p class="text-dim">Documents indexed for text search</p>
    </article>
    <article>
        <span class="metric-icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg></span>
        <h3 class="mb-2">Videos</h3>
        <p class="metric-value"><?= e((string) $fileCounts['video']) ?></p>
        <p class="text-dim">Video files stored</p>
    </article>
</section>

<div class="dashboard-grid">
    <section class="table-card">
        <h3>Badge Distribution</h3>
        <?php if (empty($badgeRows)): ?>
            <p class="muted">No data yet.</p>
        <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Badge</th>
                <th>Students</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($badgeRows as $row): ?>
            <tr>
                <td><?= badge_icon($row['badge']) ?></td>
                <td><?= e((string) $row['cnt']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </section>

    <section class="table-card">
        <h3>Recent Registrations</h3>
        <?php if (empty($recentRows)): ?>
            <p class="muted">No students registered yet.</p>
        <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Full Name</th>
                <th>Badge</th>
                <th>Files</th>
                <th>Registered At</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($recentRows as $row): ?>
            <tr data-student-row
                data-name="<?= e($row['full_name']) ?>"
                data-badge="<?= e($row['badge']) ?>"
                data-files="<?= e((string) $row['total_files']) ?>"
                data-href="<?= e(url('/student-detail?id=' . $row['id'])) ?>">
                <td><?= e($row['full_name']) ?></td>
                <td><?= badge_icon($row['badge']) ?></td>
                <td><?= e((string) $row['total_files']) ?></td>
                <td><?= fmt_date($row['created_at']) ?></td>
                <td>
                    <a class="button" href="<?= e(url('/student-detail?id=' . $row['id'])) ?>">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </section>
</div>
