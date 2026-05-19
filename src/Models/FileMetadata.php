<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

use MetaMyKad\Core\Database;

final class FileMetadata extends BaseModel
{
    protected string $table = 'file_metadata';

    public function insert(array $data): int
    {
        $this->save($data);
        return (int) Database::connection()->lastInsertId();
    }

    public function findByStudentId(int $studentId): array
    {
        return $this->query(
            'SELECT * FROM file_metadata WHERE student_id = :sid ORDER BY upload_date DESC',
            ['sid' => $studentId]
        );
    }

    public function findByStudentIdAndType(int $studentId, string $fileType): array|false
    {
        $stmt = Database::connection()->prepare(
            'SELECT * FROM file_metadata WHERE student_id = :sid AND file_type = :type LIMIT 1'
        );
        $stmt->execute(['sid' => $studentId, 'type' => $fileType]);
        return $stmt->fetch();
    }

    public function updateExtractedText(int $id, string $text): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE file_metadata SET extracted_text = :text WHERE id = :id'
        );
        $stmt->execute(['text' => $text, 'id' => $id]);
    }
}
