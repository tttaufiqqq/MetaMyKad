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
