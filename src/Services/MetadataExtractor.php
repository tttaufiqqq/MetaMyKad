<?php

declare(strict_types=1);

namespace MetaMyKad\Services;

use MetaMyKad\Models\CbrMetadata;
use MetaMyKad\Models\FileMetadata;

final class MetadataExtractor
{
    public function extract(int $fileId, string $fileType, string $storedPath): void
    {
        match ($fileType) {
            'photo' => $this->analyzePhoto($fileId, $storedPath),
            'audio' => $this->analyzeAudio($fileId, $storedPath),
            'video' => $this->analyzeVideo($fileId, $storedPath),
            'pdf'   => $this->extractPdfText($fileId, $storedPath),
            default => null,
        };
    }

    // -------------------------------------------------------------------------
    // Photo — GD-based analysis
    // -------------------------------------------------------------------------
    private function analyzePhoto(int $fileId, string $path): void
    {
        $info   = @getimagesize($path);
        $width  = $info ? (int) $info[0] : 0;
        $height = $info ? (int) $info[1] : 0;
        $imgType = $info ? (int) $info[2] : 0;

        // photo_category: formal if portrait-ish dimensions (height > width) with
        // compact width (<= 600px), non_formal otherwise
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

        // Extract original date from EXIF
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

    // -------------------------------------------------------------------------
    // Audio — ffprobe first, size-based heuristic fallback
    // -------------------------------------------------------------------------
    private function analyzeAudio(int $fileId, string $path): void
    {
        $durationSec = 0;
        $bitrate     = 0;

        $out = @shell_exec(
            'ffprobe -v error -show_entries format=duration,bit_rate'
            . ' -of default=noprint_wrappers=1 '
            . escapeshellarg($path)
            . ' 2>&1'
        );

        if ($out !== null) {
            if (preg_match('/duration=(\d+\.?\d*)/', $out, $m)) {
                $durationSec = (int) $m[1];
            }
            if (preg_match('/bit_rate=(\d+)/', $out, $m)) {
                $bitrate = (int) ((int) $m[1] / 1000); // bps → kbps
            }
        }

        // Heuristic fallback: assume 128 kbps
        if ($durationSec === 0) {
            $bitrate     = $bitrate ?: 128;
            $fileSizeBytes = filesize($path) ?: 0;
            $durationSec = (int) (($fileSizeBytes * 8) / ($bitrate * 1000));
        }

        $tier = match (true) {
            $durationSec < 60   => 'short',
            $durationSec <= 300 => 'medium',
            default             => 'long',
        };

        (new CbrMetadata())->insert([
            'file_id'             => $fileId,
            'audio_duration_sec'  => $durationSec,
            'audio_duration_tier' => $tier,
            'audio_bitrate'       => $bitrate,
        ]);

        // Extract original date from audio tags via ffprobe
        $tagOut = @shell_exec(
            'ffprobe -v error -show_entries format_tags=date'
            . ' -of default=noprint_wrappers=1 '
            . escapeshellarg($path)
            . ' 2>&1'
        );
        if ($tagOut !== null && preg_match('/TAG:date=(\S+)/', $tagOut, $m)) {
            $dt = date_create($m[1]);
            if ($dt instanceof \DateTime) {
                (new FileMetadata())->updateOriginalDate($fileId, $dt->format('Y-m-d'));
            }
        }
    }

    // -------------------------------------------------------------------------
    // Video — ffprobe first, safe defaults fallback
    // -------------------------------------------------------------------------
    private function analyzeVideo(int $fileId, string $path): void
    {
        $resolution  = 'unknown';
        $resTier     = 'SD';
        $durationSec = 0;
        $width = $height = 0;

        $streamOut = @shell_exec(
            'ffprobe -v error -select_streams v:0'
            . ' -show_entries stream=width,height'
            . ' -of default=noprint_wrappers=1 '
            . escapeshellarg($path)
            . ' 2>&1'
        );

        $formatOut = @shell_exec(
            'ffprobe -v error -show_entries format=duration'
            . ' -of default=noprint_wrappers=1 '
            . escapeshellarg($path)
            . ' 2>&1'
        );

        if ($streamOut !== null) {
            if (preg_match('/width=(\d+)/',  $streamOut, $m)) { $width  = (int) $m[1]; }
            if (preg_match('/height=(\d+)/', $streamOut, $m)) { $height = (int) $m[1]; }
        }
        if ($formatOut !== null && preg_match('/duration=(\d+\.?\d*)/', $formatOut, $m)) {
            $durationSec = (int) $m[1];
        }

        // Heuristic fallback: estimate from file size at ~1500 kbps when ffprobe unavailable
        if ($durationSec === 0) {
            $fileSizeBytes = filesize($path) ?: 0;
            if ($fileSizeBytes > 0) {
                $durationSec = (int) (($fileSizeBytes * 8) / (1500 * 1000));
            }
        }

        if ($width > 0 && $height > 0) {
            $resolution = "{$width}x{$height}";
            $resTier    = match (true) {
                $height > 1080 => 'UHD',
                $height > 720  => 'FHD',
                $height > 480  => 'HD',
                default        => 'SD',
            };
        }

        (new CbrMetadata())->insert([
            'file_id'               => $fileId,
            'video_resolution'      => $resolution,
            'video_resolution_tier' => $resTier,
            'video_duration_sec'    => $durationSec,
        ]);

        // Extract original date from video creation_time tag via ffprobe
        $tagOut = @shell_exec(
            'ffprobe -v error -show_entries format_tags=creation_time'
            . ' -of default=noprint_wrappers=1 '
            . escapeshellarg($path)
            . ' 2>&1'
        );
        if ($tagOut !== null && preg_match('/TAG:creation_time=(\S+)/', $tagOut, $m)) {
            $dt = date_create($m[1]);
            if ($dt instanceof \DateTime) {
                (new FileMetadata())->updateOriginalDate($fileId, $dt->format('Y-m-d'));
            }
        }
    }

    // -------------------------------------------------------------------------
    // PDF — pdftotext first, raw text-object fallback
    // -------------------------------------------------------------------------
    private function extractPdfText(int $fileId, string $path): void
    {
        $text = '';

        // Try pdftotext (Poppler)
        $out = @shell_exec('pdftotext ' . escapeshellarg($path) . ' - 2>&1');
        if ($out !== null
            && !str_starts_with(ltrim($out), 'Error')
            && !str_contains($out, 'is not recognized')) {
            $text = trim($out);
        }

        // Fallback: pull raw text objects from PDF byte stream
        if ($text === '') {
            $raw = @file_get_contents($path);
            if ($raw !== false) {
                preg_match_all('/BT\s+(.*?)\s+ET/s', $raw, $blocks);
                $parts = [];
                foreach ($blocks[1] as $block) {
                    preg_match_all('/\(([^)]+)\)/', $block, $textMatches);
                    foreach ($textMatches[1] as $fragment) {
                        $cleaned = trim($fragment);
                        if ($cleaned !== '') {
                            $parts[] = $cleaned;
                        }
                    }
                }
                $text = implode(' ', $parts);
            }
        }

        if ($text === '') {
            error_log("MetadataExtractor: PDF text extraction returned empty for file_id={$fileId}");
        }

        (new FileMetadata())->updateExtractedText($fileId, $text);

        // Extract original date from PDF metadata via pdfinfo
        $infoOut = @shell_exec('pdfinfo ' . escapeshellarg($path) . ' 2>&1');
        if ($infoOut !== null && preg_match('/CreationDate:\s+(.+)/i', $infoOut, $m)) {
            $dt = date_create(trim($m[1]));
            if ($dt instanceof \DateTime) {
                (new FileMetadata())->updateOriginalDate($fileId, $dt->format('Y-m-d'));
            }
        }
    }
}
