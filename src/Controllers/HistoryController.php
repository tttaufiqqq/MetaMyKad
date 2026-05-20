<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Core\Database;

final class HistoryController extends BaseController
{
    public function index(): void
    {
        $rows = Database::connection()->query(
            'SELECT rh.id, rh.ic_number, rh.action, rh.files_uploaded,
                    rh.badge_at_time, rh.registered_at,
                    COALESCE(NULLIF(rh.full_name, \'\'), s.full_name) AS full_name,
                    s.id AS student_id
             FROM registration_history rh
             LEFT JOIN students s ON s.ic_number = rh.ic_number
             ORDER BY rh.registered_at DESC, rh.id DESC
             LIMIT 50'
        )->fetchAll();

        $this->render('history', [
            'pageTitle' => 'Registration History',
            'rows'      => $rows,
        ]);
    }
}
