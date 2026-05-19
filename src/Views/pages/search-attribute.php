<?php
/** @var array  $results */
/** @var array  $activeFilters */
/** @var bool   $submitted */
/** @var string $gender */
/** @var string $stateOfBirth */
/** @var string $emailCategory */
/** @var string $badge */
/** @var string $fileType */
?>
<section class="search-panel">
    <h2>Attribute-Based Retrieval</h2>
    <form class="form-grid two-col" method="get" action="<?= e(url('/search-attribute')) ?>">
        <input type="hidden" name="_search" value="1">
        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender">
                <option value="">Any</option>
                <option value="M" <?= ($gender ?? '') === 'M' ? 'selected' : '' ?>>Male</option>
                <option value="F" <?= ($gender ?? '') === 'F' ? 'selected' : '' ?>>Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="state_of_birth">State Of Birth</label>
            <input id="state_of_birth" name="state_of_birth" type="text" value="<?= e($stateOfBirth ?? '') ?>" placeholder="e.g. Selangor">
        </div>
        <div class="form-group">
            <label for="email_category">Email Category</label>
            <select id="email_category" name="email_category">
                <option value="">Any</option>
                <option value="personal" <?= ($emailCategory ?? '') === 'personal' ? 'selected' : '' ?>>Personal</option>
                <option value="student"  <?= ($emailCategory ?? '') === 'student'  ? 'selected' : '' ?>>Student</option>
                <option value="work"     <?= ($emailCategory ?? '') === 'work'     ? 'selected' : '' ?>>Work</option>
            </select>
        </div>
        <div class="form-group">
            <label for="badge">Badge</label>
            <select id="badge" name="badge">
                <option value="">Any</option>
                <option value="Pendaftar" <?= ($badge ?? '') === 'Pendaftar' ? 'selected' : '' ?>>Pendaftar</option>
                <option value="Pelajar"   <?= ($badge ?? '') === 'Pelajar'   ? 'selected' : '' ?>>Pelajar</option>
                <option value="Aktif"     <?= ($badge ?? '') === 'Aktif'     ? 'selected' : '' ?>>Aktif</option>
                <option value="Dedikasi"  <?= ($badge ?? '') === 'Dedikasi'  ? 'selected' : '' ?>>Dedikasi</option>
                <option value="Cemerlang" <?= ($badge ?? '') === 'Cemerlang' ? 'selected' : '' ?>>Cemerlang</option>
            </select>
        </div>
        <div class="form-group">
            <label for="file_type">File Type</label>
            <select id="file_type" name="file_type">
                <option value="">Any</option>
                <option value="photo" <?= ($fileType ?? '') === 'photo' ? 'selected' : '' ?>>Photo</option>
                <option value="audio" <?= ($fileType ?? '') === 'audio' ? 'selected' : '' ?>>Audio</option>
                <option value="pdf"   <?= ($fileType ?? '') === 'pdf'   ? 'selected' : '' ?>>PDF</option>
                <option value="video" <?= ($fileType ?? '') === 'video' ? 'selected' : '' ?>>Video</option>
            </select>
        </div>
        <div class="form-actions">
            <button class="button" type="submit">Search</button>
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
        <p class="muted" style="padding:1rem;">Use the filters above and click Search to find students.</p>
    <?php elseif (empty($results)): ?>
        <p class="muted" style="padding:1rem;">No matching records found.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Full Name</th>
                <th>IC Number</th>
                <th>Gender</th>
                <th>State</th>
                <th>Badge</th>
                <th>Files</th>
                <th>File Type</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($results as $row): ?>
            <tr>
                <td><?= e($row['full_name']) ?></td>
                <td><?= e($row['ic_number']) ?></td>
                <td><?= e($row['gender']) ?></td>
                <td><?= e($row['state_of_birth']) ?></td>
                <td><?= e($row['stored_badge']) ?></td>
                <td><?= e((string) $row['total_files']) ?></td>
                <td><?= e((string) ($row['file_type'] ?? '—')) ?></td>
                <td>
                    <a class="button" href="<?= e(url('/student-detail?id=' . $row['student_id'])) ?>">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
