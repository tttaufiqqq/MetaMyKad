<?php
/** @var array $student */
/** @var array $files */
/** @var array $history */

use MetaMyKad\Core\Auth;

$isOwner = Auth::check() && (int) Auth::user()['id'] === (int) $student['student_id'];

$fileIcons = [
    'photo' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
    'audio' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
    'pdf'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
    'video' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>',
];

$fileAccept = [
    'photo' => 'image/jpeg,image/png,.jpg,.jpeg,.png',
    'audio' => 'audio/mpeg,audio/wav,.mp3,.wav',
    'pdf'   => 'application/pdf,.pdf',
    'video' => 'video/mp4,video/quicktime,video/x-msvideo,.mp4,.mov,.avi',
];

function fmt_bytes(int $bytes): string {
    if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
    if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
    return $bytes . ' B';
}

function fmt_seconds(int $s): string {
    $m = intdiv($s, 60);
    return $m . ':' . str_pad((string) ($s % 60), 2, '0', STR_PAD_LEFT);
}

// Index files by type for easy lookup in edit form
$filesByType = [];
foreach ($files as $f) {
    $filesByType[$f['file_type']] = $f;
}

// A stub account has ic_number === null (auto-created from mmdb2026.vstu on first login).
$isStub = $isOwner && $student['ic_number'] === null;
