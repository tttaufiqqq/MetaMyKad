<?php

declare(strict_types=1);

namespace MetaMyKad\Services;

use MetaMyKad\Models\CbrMetadata;
use MetaMyKad\Models\FileMetadata;

trait MetadataExtractorPhotoTrait
{
    private function analyzePhoto(int $fileId, string $path): void
    {
        $info    = @getimagesize($path);
        $width   = $info ? (int) $info[0] : 0;
        $height  = $info ? (int) $info[1] : 0;
        $imgType = $info ? (int) $info[2] : 0;

        $photoCategory = 'non_formal';
        if ($width > 0 && $height > 0 && ($height / $width) >= 1.15 && $width <= 600) {
            $photoCategory = 'formal';
        }

        try {
            [$r, $g, $b, $variance] = $this->sampleCorners($path, $imgType);
        } catch (\Throwable) {
            [$r, $g, $b, $variance] = [200, 200, 200, 0.0];
        }

        (new CbrMetadata())->insert([
            'file_id'               => $fileId,
            'photo_category'        => $photoCategory,
            'dominant_bg_color'     => sprintf('#%02x%02x%02x', $r, $g, $b),
            'bg_color_variance'     => round($variance, 2),
            'dominant_expression'   => 'neutral',
            'expression_confidence' => 0.5,
        ]);

        $exif = @exif_read_data($path);
        if ($exif !== false) {
            $raw = $exif['DateTimeOriginal'] ?? $exif['DateTime'] ?? null;
            if ($raw !== null) {
                $dt = \DateTime::createFromFormat('Y:m:d H:i:s', (string) $raw);
                if ($dt instanceof \DateTime) {
                    (new FileMetadata())->updateOriginalDate($fileId, $dt->format('Y-m-d'));
                }
            }
        }
    }

    private function sampleCorners(string $path, int $imgType): array
    {
        $img = null;
        if ($imgType === IMAGETYPE_JPEG) {
            $img = @imagecreatefromjpeg($path);
        } elseif ($imgType === IMAGETYPE_PNG) {
            $img = @imagecreatefrompng($path);
        }

        if (!$img) {
            return [200, 200, 200, 0.0];
        }

        $w = imagesx($img);
        $h = imagesy($img);

        $samples = [
            imagecolorat($img, 0, 0),
            imagecolorat($img, $w - 1, 0),
            imagecolorat($img, 0, $h - 1),
            imagecolorat($img, $w - 1, $h - 1),
        ];
        imagedestroy($img);

        $rs = $gs = $bs = [];
        foreach ($samples as $color) {
            $rs[] = ($color >> 16) & 0xFF;
            $gs[] = ($color >> 8)  & 0xFF;
            $bs[] = $color & 0xFF;
        }

        $r = (int) (array_sum($rs) / 4);
        $g = (int) (array_sum($gs) / 4);
        $b = (int) (array_sum($bs) / 4);

        $lums = array_map(
            static fn (int $rr, int $gg, int $bb): float => 0.299 * $rr + 0.587 * $gg + 0.114 * $bb,
            $rs, $gs, $bs
        );
        $mean     = array_sum($lums) / 4;
        $variance = array_sum(array_map(static fn (float $l): float => ($l - $mean) ** 2, $lums)) / 4;

        return [$r, $g, $b, $variance];
    }
}
