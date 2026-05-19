<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

use MetaMyKad\Core\Database;

final class CbrMetadata extends BaseModel
{
    protected string $table = 'cbr_metadata';

    public function insert(array $data): int
    {
        $this->save($data);
        return (int) Database::connection()->lastInsertId();
    }

    public function findByFileId(int $fileId): array|false
    {
        $stmt = Database::connection()->prepare(
            'SELECT * FROM cbr_metadata WHERE file_id = :fid LIMIT 1'
        );
        $stmt->execute(['fid' => $fileId]);
        return $stmt->fetch();
    }
}
