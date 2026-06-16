<?php
/** @var array  $students */
/** @var string $name */
/** @var string $badge */

use MetaMyKad\Core\Auth;

$badges    = ['Pendaftar', 'Pelajar', 'Aktif', 'Dedikasi', 'Cemerlang'];
$registered = count(array_filter($students, fn($s) => $s['metamykad_id'] !== null));
?>

<section class="card">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
        <div>
            <h2>All Students <span style="font-size:1rem; font-weight:400; color:var(--color-muted);">(<?= count($students) ?>)</span></h2>
            <p class="muted" style="margin-bottom:0.5rem;"><?= $registered ?> of <?= count($students) ?> students have a MetaMyKad profile.</p>
            <button type="button" class="badge-guide-trigger" data-badge-guide-open>
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <circle cx="6" cy="6" r="5.5" stroke="currentColor"/>
                    <path d="M6 5.5v3M6 3.5h.01" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
                Badge Guide
            </button>
        </div>
        <a class="button" href="<?= e(url('/register')) ?>">Register Student</a>
    </div>
</section>

<section class="search-panel">
    <form method="get" action="<?= e(url('/students')) ?>" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
        <div class="form-group" style="flex:1; min-width:200px;">
            <input type="text" id="name" name="name" value="<?= e($name) ?>" placeholder="Search by name">
            <label for="name">Name</label>
        </div>
        <div class="form-group" style="min-width:180px;">
            <select id="badge" name="badge">
                <option value="">All Badges</option>
                <?php foreach ($badges as $b): ?>
                <option value="<?= e($b) ?>" <?= $badge === $b ? 'selected' : '' ?>><?= e($b) ?></option>
                <?php endforeach; ?>
                <option value="unregistered" <?= $badge === 'unregistered' ? 'selected' : '' ?>>Profile Not Complete</option>
            </select>
            <label for="badge">Badge</label>
        </div>
        <button class="button" type="submit">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Search
        </button>
        <?php if ($name !== '' || $badge !== ''): ?>
        <a class="button secondary" href="<?= e(url('/students')) ?>">Clear</a>
        <?php endif; ?>
    </form>
</section>

<?php if (empty($students)): ?>
<section class="card">
    <p class="muted">No students found.
        <?php if ($name === '' && $badge === ''): ?>
        <a href="<?= e(url('/register')) ?>">Register the first student.</a>
        <?php endif; ?>
    </p>
</section>
<?php else: ?>
<div class="students-grid">
    <?php foreach ($students as $student): ?>
    <?php
        $isRegistered = $student['metamykad_id'] !== null;
        // Logged-in users must not be linked to another student's /register page.
        // Only anonymous visitors get the self-registration link for unregistered cards.
        if ($isRegistered) {
            $cardTag  = 'a';
            $cardHref = 'href="' . e(url('/student-detail?id=' . $student['metamykad_id'])) . '"';
        } elseif (!Auth::check()) {
            $cardTag  = 'a';
            $cardHref = 'href="' . e(url('/register?matric=' . urlencode($student['matric_no']))) . '"';
        } else {
            $cardTag  = 'div';
            $cardHref = '';
        }
    ?>
    <<?= $cardTag ?> class="student-card <?= $isRegistered ? '' : 'student-card--unregistered' ?>" <?= $cardHref ?>>
        <div class="student-card__photo">
            <?php if ($student['photo_id'] !== null): ?>
            <img src="<?= e(url('/file?id=' . $student['photo_id'])) ?>"
                 alt="<?= e($student['full_name']) ?>"
                 loading="lazy">
            <?php else: ?>
            <span class="student-card__avatar">
                <?= e(mb_strtoupper(mb_substr((string) $student['full_name'], 0, 1))) ?>
            </span>
            <?php endif; ?>
        </div>
        <p class="student-card__name"><?= e($student['full_name']) ?></p>
        <?php if ($isRegistered): ?>
        <p class="student-card__badge"><?= badge_icon((string) $student['badge'], '1rem') ?></p>
        <?php else: ?>
        <p class="student-card__badge student-card__badge--none">Profile Not Complete</p>
        <?php endif; ?>
    </<?= $cardTag ?>>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<style>
.students-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
    margin-bottom: var(--space-3);
}

@media (max-width: 960px) {
    .students-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}

@media (max-width: 640px) {
    .students-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

.student-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.65rem;
    padding: 1.5rem 1rem 1.25rem;
    background: linear-gradient(180deg, rgba(18, 27, 54, 0.88), rgba(8, 14, 33, 0.8));
    border: 1px solid var(--color-line);
    border-radius: var(--radius-md);
    text-decoration: none;
    text-align: center;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease, background 0.2s ease;
    animation: fade-in-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    position: relative;
    overflow: hidden;
}

.student-card::before {
    content: "";
    position: absolute;
    inset: 0 0 auto 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(143, 235, 255, 0.4), transparent);
    pointer-events: none;
}

.student-card:hover {
    transform: translateY(-4px);
    border-color: rgba(57, 197, 255, 0.3);
    background: linear-gradient(180deg, rgba(19, 29, 58, 0.92), rgba(8, 14, 33, 0.86));
}

.student-card__photo {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(57, 197, 255, 0.22);
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(57, 197, 255, 0.08);
    flex-shrink: 0;
}

.student-card__photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.student-card__avatar {
    font-family: var(--font-heading);
    font-size: 1.65rem;
    font-weight: 800;
    color: var(--color-brand-bright);
    line-height: 1;
}

.student-card__name {
    font-size: 0.9rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.3;
    margin: 0;
}

.student-card__badge {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-family: var(--font-mono);
    color: var(--color-secondary);
    background: rgba(113, 239, 192, 0.1);
    border: 1px solid rgba(113, 239, 192, 0.18);
    padding: 0.22rem 0.6rem;
    border-radius: var(--radius-pill);
    margin: 0;
}

.student-card--unregistered {
    opacity: 0.55;
    border-style: dashed;
}

.student-card--unregistered:hover {
    opacity: 0.85;
}

.student-card__badge--none {
    color: #ff6b6b;
    background: rgba(255, 107, 107, 0.1);
    border-color: rgba(255, 107, 107, 0.25);
}

/* Stagger student cards in rows of 4 */
.student-card:nth-child(4n+2) { animation-delay: 55ms; }
.student-card:nth-child(4n+3) { animation-delay: 110ms; }
.student-card:nth-child(4n+4) { animation-delay: 165ms; }
</style>
