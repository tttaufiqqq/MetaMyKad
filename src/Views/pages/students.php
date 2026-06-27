<?php
/** @var array  $students */
/** @var string $name */
/** @var string $badge */

$badges     = ['Pendaftar', 'Pelajar', 'Aktif', 'Dedikasi', 'Cemerlang'];
$registered = count(array_filter($students, fn($s) => $s['metamykad_id'] !== null));

require src_path('Views/pages/students/part-header.php');
require src_path('Views/pages/students/part-search.php');
require src_path('Views/pages/students/part-grid.php');
require src_path('Views/pages/students/part-style.php');
