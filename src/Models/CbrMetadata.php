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

    public function updateDuration(int $fileId, string $fileType, int $durationSec): void
    {
        if ($fileType === 'audio') {
            $tier = match (true) {
                $durationSec < 60   => 'short',
                $durationSec <= 300 => 'medium',
                default             => 'long',
            };
            $stmt = Database::connection()->prepare(
                'UPDATE cbr_metadata SET audio_duration_sec = :sec, audio_duration_tier = :tier WHERE file_id = :fid'
            );
            $stmt->execute(['sec' => $durationSec, 'tier' => $tier, 'fid' => $fileId]);
        } elseif ($fileType === 'video') {
            $stmt = Database::connection()->prepare(
                'UPDATE cbr_metadata SET video_duration_sec = :sec WHERE file_id = :fid'
            );
            $stmt->execute(['sec' => $durationSec, 'fid' => $fileId]);
        }
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
