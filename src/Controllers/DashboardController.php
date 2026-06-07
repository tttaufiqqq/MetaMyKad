<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Core\Database;

final class DashboardController extends BaseController
{
    public function index(): void
    {
        $pdo = Database::connection();

        $totalStudents = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();

        $fileCountRows = $pdo->query(
            'SELECT file_type, COUNT(*) AS cnt FROM file_metadata GROUP BY file_type'
        )->fetchAll();
        $fileCounts = ['photo' => 0, 'audio' => 0, 'pdf' => 0, 'video' => 0];
        foreach ($fileCountRows as $row) {
            $fileCounts[(string) $row['file_type']] = (int) $row['cnt'];
        }

        $badgeRows = $pdo->query(
            'SELECT badge, COUNT(*) AS cnt FROM students GROUP BY badge
             ORDER BY FIELD(badge,"Cemerlang","Dedikasi","Aktif","Pelajar","Pendaftar")'
        )->fetchAll();

        $recentRows = $pdo->query(
            'SELECT s.id, s.full_name, s.badge,
                    COALESCE(fc.total_files, 0) AS total_files,
                    s.created_at
             FROM students s
             LEFT JOIN vw_student_file_counts fc ON fc.student_id = s.id
             ORDER BY s.id DESC LIMIT 10'
        )->fetchAll();

        $this->render('dashboard', [
            'pageTitle'     => 'Dashboard',
            'totalStudents' => $totalStudents,
            'fileCounts'    => $fileCounts,
            'badgeRows'     => $badgeRows,
            'recentRows'    => $recentRows,
        ]);
    }
}
