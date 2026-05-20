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
    <p class="muted" style="margin-bottom:1.25rem;">
        Filter students by structured profile attributes — gender, state of birth, email category,
        badge level, and uploaded file type. All filters are optional and can be combined freely.
    </p>
    <form class="form-grid two-col" method="get" action="<?= e(url('/search-attribute')) ?>">
        <input type="hidden" name="_search" value="1">
        <div class="form-group">
            <select id="gender" name="gender">
                <option value="">Any</option>
                <option value="M" <?= ($gender ?? '') === 'M' ? 'selected' : '' ?>>Male</option>
                <option value="F" <?= ($gender ?? '') === 'F' ? 'selected' : '' ?>>Female</option>
            </select>
            <label for="gender">Gender</label>
        </div>
        <div class="form-group">
            <select id="state_of_birth" name="state_of_birth">
                <option value="">Any</option>
                <?php foreach ([
                    'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan',
                    'Pahang', 'Pulau Pinang', 'Perak', 'Perlis', 'Selangor',
                    'Terengganu', 'Sabah', 'Sarawak',
                    'Wilayah Persekutuan Kuala Lumpur',
                    'Wilayah Persekutuan Labuan',
                    'Wilayah Persekutuan Putrajaya',
                ] as $state): ?>
                <option value="<?= e($state) ?>" <?= ($stateOfBirth ?? '') === $state ? 'selected' : '' ?>><?= e($state) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="state_of_birth">State Of Birth</label>
        </div>
        <div class="form-group">
            <select id="email_category" name="email_category">
                <option value="">Any</option>
                <option value="Personal" <?= ($emailCategory ?? '') === 'Personal' ? 'selected' : '' ?>>Personal</option>
                <option value="Student"  <?= ($emailCategory ?? '') === 'Student'  ? 'selected' : '' ?>>Student</option>
                <option value="Work"     <?= ($emailCategory ?? '') === 'Work'     ? 'selected' : '' ?>>Work</option>
            </select>
            <label for="email_category">Email Category</label>
        </div>
        <div class="form-group">
            <select id="badge" name="badge">
                <option value="">Any</option>
                <option value="Pendaftar" <?= ($badge ?? '') === 'Pendaftar' ? 'selected' : '' ?>>Pendaftar</option>
                <option value="Pelajar"   <?= ($badge ?? '') === 'Pelajar'   ? 'selected' : '' ?>>Pelajar</option>
                <option value="Aktif"     <?= ($badge ?? '') === 'Aktif'     ? 'selected' : '' ?>>Aktif</option>
                <option value="Dedikasi"  <?= ($badge ?? '') === 'Dedikasi'  ? 'selected' : '' ?>>Dedikasi</option>
                <option value="Cemerlang" <?= ($badge ?? '') === 'Cemerlang' ? 'selected' : '' ?>>Cemerlang</option>
            </select>
            <label for="badge">Badge</label>
        </div>
        <div class="form-group">
            <select id="file_type" name="file_type">
                <option value="">Any</option>
                <option value="photo" <?= ($fileType ?? '') === 'photo' ? 'selected' : '' ?>>Photo</option>
                <option value="audio" <?= ($fileType ?? '') === 'audio' ? 'selected' : '' ?>>Audio</option>
                <option value="pdf"   <?= ($fileType ?? '') === 'pdf'   ? 'selected' : '' ?>>PDF</option>
                <option value="video" <?= ($fileType ?? '') === 'video' ? 'selected' : '' ?>>Video</option>
            </select>
            <label for="file_type">File Type</label>
        </div>
        <div class="form-actions">
            <button class="button" type="submit">Search</button>
            <a class="button secondary" href="<?= e(url('/search-attribute')) ?>">Clear Filters</a>
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
            <tr data-student-row
                data-name="<?= e($row['full_name']) ?>"
                data-ic="<?= e($row['ic_number']) ?>"
                data-gender="<?= e($row['gender']) ?>"
                data-state="<?= e($row['state_of_birth']) ?>"
                data-badge="<?= e($row['stored_badge']) ?>"
                data-files="<?= e((string) $row['total_files']) ?>"
                data-file-type="<?= e((string) ($row['file_type'] ?? '')) ?>"
                data-href="<?= e(url('/student-detail?id=' . $row['student_id'])) ?>">
                <td><?= e($row['full_name']) ?></td>
                <td><?= e($row['ic_number']) ?></td>
                <td><?= e($row['gender']) ?></td>
                <td><?= e($row['state_of_birth']) ?></td>
                <td><?= badge_icon($row['stored_badge']) ?></td>
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
