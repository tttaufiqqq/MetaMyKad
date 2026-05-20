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
        <h3 class="mb-2">Total Students</h3>
        <p class="metric-value"><?= e((string) $totalStudents) ?></p>
        <p class="text-dim">Registered in the system</p>
    </article>
    <article>
        <h3 class="mb-2">Photos</h3>
        <p class="metric-value"><?= e((string) $fileCounts['photo']) ?></p>
        <p class="text-dim">Photo files stored</p>
    </article>
    <article>
        <h3 class="mb-2">Audio</h3>
        <p class="metric-value"><?= e((string) $fileCounts['audio']) ?></p>
        <p class="text-dim">Audio files stored</p>
    </article>
    <article>
        <h3 class="mb-2">PDFs</h3>
        <p class="metric-value"><?= e((string) $fileCounts['pdf']) ?></p>
        <p class="text-dim">Documents indexed for text search</p>
    </article>
    <article>
        <h3 class="mb-2">Videos</h3>
        <p class="metric-value"><?= e((string) $fileCounts['video']) ?></p>
        <p class="text-dim">Video files stored</p>
    </article>
</section>

<div class="dashboard-grid">
    <section class="table-card">
        <h3>Badge Distribution</h3>
        <?php if (empty($badgeRows)): ?>
            <p class="muted" style="padding:1rem;">No data yet.</p>
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
            <p class="muted" style="padding:1rem;">No students registered yet.</p>
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
                <td><?= e($row['created_at']) ?></td>
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
