<?php

declare(strict_types=1);

namespace MetaMyKad\Services;

use MetaMyKad\Models\FileMetadata;

trait MetadataExtractorPdfTrait
{
    private function extractPdfText(int $fileId, string $path): void
    {
        $text = '';

        $out = $this->shell('pdftotext ' . escapeshellarg($path) . ' - 2>&1');
        if ($out !== null
            && !str_starts_with(ltrim($out), 'Error')
            && !str_contains($out, 'is not recognized')) {
            $text = trim($out);
        }

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

        $infoOut = $this->shell('pdfinfo ' . escapeshellarg($path) . ' 2>&1');
        if ($infoOut !== null && preg_match('/CreationDate:\s+(.+)/i', $infoOut, $m)) {
            $dt = date_create(trim($m[1]));
            if ($dt instanceof \DateTime) {
                (new FileMetadata())->updateOriginalDate($fileId, $dt->format('Y-m-d'));
            }
        }
    }
}
