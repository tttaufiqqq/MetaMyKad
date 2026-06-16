<?php
$badgeGuide = [
    [
        'name'  => 'Pendaftar',
        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="5"/><path d="M8 13.5 7 22l5-3 5 3-1-8.5"/></svg>',
        'anim'  => 'badge-anim--pulse',
        'label' => 'Registrant',
        'desc'  => 'Just registered. No multimedia files have been uploaded yet.',
        'files' => 0,
    ],
    [
        'name'  => 'Pelajar',
        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 2.2 2.7 4 6 4s6-1.8 6-4v-5"/><line x1="22" y1="10" x2="22" y2="15"/></svg>',
        'anim'  => 'badge-anim--float',
        'label' => 'Student',
        'desc'  => 'Getting started — 1 out of 4 file types uploaded.',
        'files' => 1,
    ],
    [
        'name'  => 'Aktif',
        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#facc15" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
        'anim'  => 'badge-anim--zap',
        'label' => 'Active',
        'desc'  => 'Making progress — 2 out of 4 file types uploaded.',
        'files' => 2,
    ],
    [
        'name'  => 'Dedikasi',
        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>',
        'anim'  => 'badge-anim--flicker',
        'label' => 'Dedicated',
        'desc'  => 'Almost complete — 3 out of 4 file types uploaded.',
        'files' => 3,
    ],
    [
        'name'  => 'Cemerlang',
        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#eab308" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/></svg>',
        'anim'  => 'badge-anim--glow',
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
                <div class="badge-guide-item__icon <?= e($b['anim']) ?>">
                    <?= $b['icon'] ?>
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

<style>
/* Pendaftar — slow breathe: humble, just starting out */
@keyframes badge-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.5; transform: scale(0.88); }
}
.badge-anim--pulse { animation: badge-pulse 3s ease-in-out infinite; }

/* Pelajar — gentle float: knowledge rising */
@keyframes badge-float {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-6px); }
}
.badge-anim--float { animation: badge-float 2.4s ease-in-out infinite; }

/* Aktif — quick zap burst then pause: bursts of energy */
@keyframes badge-zap {
    0%   { transform: scale(1) rotate(0deg); }
    10%  { transform: scale(1.22) rotate(-8deg); }
    20%  { transform: scale(0.92) rotate(6deg); }
    30%  { transform: scale(1.1) rotate(-4deg); }
    40%  { transform: scale(1); }
    100% { transform: scale(1); }
}
.badge-anim--zap { animation: badge-zap 2.2s ease-in-out infinite; }

/* Dedikasi — organic flicker: flame dancing */
@keyframes badge-flicker {
    0%, 100% { transform: scaleY(1)   scaleX(1);    }
    20%       { transform: scaleY(1.1) scaleX(0.95); }
    40%       { transform: scaleY(0.94) scaleX(1.04); }
    60%       { transform: scaleY(1.07) scaleX(0.97); }
    80%       { transform: scaleY(0.97) scaleX(1.02); }
}
.badge-anim--flicker {
    transform-origin: center bottom;
    animation: badge-flicker 1.6s ease-in-out infinite;
}

/* Cemerlang — golden glow pulse: prestige shining */
@keyframes badge-glow {
    0%, 100% { filter: drop-shadow(0 0 2px rgba(234,179,8,0.3)); transform: scale(1); }
    50%       { filter: drop-shadow(0 0 8px rgba(234,179,8,0.9)) drop-shadow(0 0 16px rgba(234,179,8,0.4)); transform: scale(1.08); }
}
.badge-anim--glow { animation: badge-glow 2s ease-in-out infinite; }

@media (prefers-reduced-motion: reduce) {
    .badge-anim--pulse,
    .badge-anim--float,
    .badge-anim--zap,
    .badge-anim--flicker,
    .badge-anim--glow { animation: none; }
}
</style>
