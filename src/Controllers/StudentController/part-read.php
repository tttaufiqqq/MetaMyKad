<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Models\CbrMetadata;
use MetaMyKad\Models\FileMetadata;
use MetaMyKad\Models\RegistrationHistory;
use MetaMyKad\Models\Student;
use MetaMyKad\Models\StudentProfileSummaryView;

trait StudentControllerReadTrait
{
    public function index(): void
    {
        $students = (new Student())->getAllFromCentral();

        $name  = trim((string) ($_GET['name'] ?? ''));
        $badge = trim((string) ($_GET['badge'] ?? ''));

        if ($name !== '') {
            $lower = strtolower($name);
            $students = array_values(array_filter(
                $students,
                fn($s) => str_contains(strtolower((string) $s['full_name']), $lower)
            ));
        }

        if ($badge === 'unregistered') {
            $students = array_values(array_filter(
                $students,
                fn($s) => $s['metamykad_id'] === null
            ));
        } elseif ($badge !== '') {
            $students = array_values(array_filter(
                $students,
                fn($s) => $s['badge'] === $badge
            ));
        }

        $this->render('students', [
            'pageTitle' => 'All Students',
            'students'  => $students,
            'name'      => $name,
            'badge'     => $badge,
        ]);
    }

    public function show(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            $this->flash('error', 'Invalid student reference.');
            $this->redirect('/dashboard');
        }

        $summaryModel = new StudentProfileSummaryView();
        $student      = $summaryModel->find($id);

        if ($student === false) {
            http_response_code(404);
            require src_path('Views/errors/404.php');
            exit;
        }

        $fileModel = new FileMetadata();
        $files     = $fileModel->findByStudentId($id);

        $cbrModel = new CbrMetadata();
        foreach ($files as &$file) {
            $cbr          = $cbrModel->findByFileId((int) $file['id']);
            $file['cbr']  = $cbr !== false ? $cbr : [];
            $file['tags'] = $fileModel->query(
                'SELECT t.tag_name FROM tags t
                 JOIN file_tags ft ON ft.tag_id = t.id
                 WHERE ft.file_id = :fid',
                ['fid' => (int) $file['id']]
            );
        }
        unset($file);

        $historyModel = new RegistrationHistory();
        $history      = $historyModel->getByIc((string) $student['ic_number']);

        $this->render('student-detail', [
            'pageTitle' => 'Student Detail',
            'student'   => $student,
            'files'     => $files,
            'history'   => $history,
        ]);
    }
}
