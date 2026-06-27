<?php

declare(strict_types=1);

namespace MetaMyKad\Services;

use MetaMyKad\Models\CbrMetadata;
use MetaMyKad\Models\FileMetadata;

trait MetadataExtractorAudioVideoTrait
{
    private function analyzeAudio(int $fileId, string $path): void
    {
        $durationSec = 0;
        $bitrate     = 0;

        $out = $this->shell(
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
                $bitrate = (int) ((int) $m[1] / 1000);
            }
        }

        if ($durationSec === 0) {
            $bitrate       = $bitrate ?: 128;
            $fileSizeBytes = filesize($path) ?: 0;
            $durationSec   = (int) (($fileSizeBytes * 8) / ($bitrate * 1000));
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

        $tagOut = $this->shell(
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

    private function analyzeVideo(int $fileId, string $path): void
    {
        $resolution  = 'unknown';
        $resTier     = 'SD';
        $durationSec = 0;
        $width = $height = 0;

        $streamOut = $this->shell(
            'ffprobe -v error -select_streams v:0'
            . ' -show_entries stream=width,height'
            . ' -of default=noprint_wrappers=1 '
            . escapeshellarg($path)
            . ' 2>&1'
        );

        $formatOut = $this->shell(
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

        $tagOut = $this->shell(
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
}
