<?php

declare(strict_types=1);

namespace MetaMyKad\Services;

use MetaMyKad\Models\CbrMetadata;
use MetaMyKad\Models\Tag;

final class AutoTagger
{
    public function tag(int $fileId, string $fileType): void
    {
        $cbr = (new CbrMetadata())->findByFileId($fileId);
        $cbr = $cbr !== false ? $cbr : [];

        $tags = match ($fileType) {
            'photo' => $this->photoTags($cbr),
            'audio' => $this->audioTags($cbr),
            'video' => $this->videoTags($cbr),
            'pdf'   => ['pdf'],
            default => [],
        };

        $tagModel = new Tag();
        $attached = 0;

        foreach ($tags as $name) {
            if ($attached >= 10) break;
            $tagId = $tagModel->findOrCreate($name);
            $tagModel->attachToFile($fileId, $tagId);
            $attached++;
        }
    }

    private function photoTags(array $cbr): array
    {
        $tags = ['photo'];

        $category = $cbr['photo_category'] ?? null;
        if ($category === 'formal') {
            $tags[] = 'formal';
        } elseif ($category === 'non_formal') {
            $tags[] = 'non-formal';
        }

        $expression = isset($cbr['dominant_expression'])
            ? strtolower(trim((string) $cbr['dominant_expression']))
            : null;
        if ($expression !== null && $expression !== '') {
            $tags[] = $expression;
        }

        return $tags;
    }

    private function audioTags(array $cbr): array
    {
        $tags = ['audio'];

        $tier = isset($cbr['audio_duration_tier'])
            ? strtolower((string) $cbr['audio_duration_tier'])
            : null;
        if ($tier !== null && $tier !== '') {
            $tags[] = $tier;
        }

        $bitrate = (int) ($cbr['audio_bitrate'] ?? 0);
        if ($bitrate >= 256) {
            $tags[] = 'high quality';
        } elseif ($bitrate >= 128) {
            $tags[] = 'standard quality';
        } elseif ($bitrate > 0) {
            $tags[] = 'low quality';
        }

        return $tags;
    }

    private function videoTags(array $cbr): array
    {
        $tags = ['video'];

        $tier = isset($cbr['video_resolution_tier'])
            ? strtolower((string) $cbr['video_resolution_tier'])
            : null;
        if ($tier !== null && $tier !== '') {
            $tags[] = $tier;
        }

        return $tags;
    }
}
