<?php
$badgeGuide = [
    [
        'name'  => 'Pendaftar',
        'file'  => 'pendaftar.png',
        'label' => 'Registrant',
        'desc'  => 'Just registered. No multimedia files have been uploaded yet.',
        'files' => 0,
    ],
    [
        'name'  => 'Pelajar',
        'file'  => 'pelajar.png',
        'label' => 'Student',
        'desc'  => 'Getting started — 1 out of 4 file types uploaded.',
        'files' => 1,
    ],
    [
        'name'  => 'Aktif',
        'file'  => 'aktif.png',
        'label' => 'Active',
        'desc'  => 'Making progress — 2 out of 4 file types uploaded.',
        'files' => 2,
    ],
    [
        'name'  => 'Dedikasi',
        'file'  => 'dedikasi.png',
        'label' => 'Dedicated',
        'desc'  => 'Almost complete — 3 out of 4 file types uploaded.',
        'files' => 3,
    ],
    [
        'name'  => 'Cemerlang',
        'file'  => 'cemerlang.png',
        'label' => 'Excellent',
        'desc'  => 'Full profile — all 4 file types uploaded: photo, audio, PDF, and video.',
        'files' => 4,
    ],
];
?>
<div class="badge-guide-modal hidden" id="badge-guide-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="bgm-title">
    <div class="badge-guide-modal__panel">
        <button class="student-modal__close" id="badge-guide-close" aria-label="Close">&times;</button>
        <p class="student-modal__eyebrow">Badge System</p>
        <h3 id="bgm-title" class="badge-guide-modal__title">Badge Guide</h3>
        <p class="badge-guide-modal__intro">
            Badges are earned based on how many of the 4 multimedia file types a student has uploaded:
            <strong>photo</strong>, <strong>audio</strong>, <strong>PDF</strong>, and <strong>video</strong>.
        </p>
        <ul class="badge-guide-list">
            <?php foreach ($badgeGuide as $b): ?>
            <li class="badge-guide-item">
                <div class="badge-guide-item__icon">
                    <img src="<?= e(asset('images/nav/' . $b['file'])) ?>" alt="<?= e($b['name']) ?>" loading="lazy">
                </div>
                <div class="badge-guide-item__body">
                    <span class="badge-guide-item__name"><?= e($b['name']) ?></span>
                    <span class="badge-guide-item__label"><?= e($b['label']) ?></span>
                    <p class="badge-guide-item__desc"><?= e($b['desc']) ?></p>
                </div>
                <div class="badge-guide-item__pips">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                    <span class="badge-pip <?= $i <= $b['files'] ? 'is-filled' : '' ?>"></span>
                    <?php endfor; ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <div class="student-modal__actions">
            <button type="button" class="button secondary" id="badge-guide-cancel">Close</button>
        </div>
    </div>
</div>
