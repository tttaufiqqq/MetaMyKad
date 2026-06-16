<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

use MetaMyKad\Core\Database;

final class Tag extends BaseModel
{
    protected string $table = 'tags';

    public function findOrCreate(string $name): int
    {
        $stmt = Database::connection()->prepare(
            'SELECT id FROM tags WHERE tag_name = :name LIMIT 1'
        );
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch();

        if ($row !== false) {
            return (int) $row['id'];
        }

        $this->save(['tag_name' => $name]);
        return (int) Database::connection()->lastInsertId();
    }

    public function attachToFile(int $fileId, int $tagId): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT IGNORE INTO file_tags (file_id, tag_id) VALUES (:fid, :tid)'
        );
        $stmt->execute(['fid' => $fileId, 'tid' => $tagId]);
    }

    public function findByFileId(int $fileId): array
    {
        return $this->query(
            'SELECT t.tag_name FROM tags t
             JOIN file_tags ft ON ft.tag_id = t.id
             WHERE ft.file_id = :fid
             ORDER BY t.tag_name',
            ['fid' => $fileId]
        );
    }

    public function detachAllFromFile(int $fileId): void
    {
        $stmt = Database::connection()->prepare(
            'DELETE FROM file_tags WHERE file_id = :fid'
        );
        $stmt->execute(['fid' => $fileId]);
    }

    public function detachByName(int $fileId, string $tagName): void
    {
        Database::connection()->prepare(
            'DELETE ft FROM file_tags ft
             JOIN tags t ON t.id = ft.tag_id
             WHERE ft.file_id = :fid AND t.tag_name = :name'
        )->execute(['fid' => $fileId, 'name' => $tagName]);
    }
}
