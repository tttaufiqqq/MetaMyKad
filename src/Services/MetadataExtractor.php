<?php

declare(strict_types=1);

namespace MetaMyKad\Services;

require_once src_path('Services/MetadataExtractor/part-photo.php');
require_once src_path('Services/MetadataExtractor/part-audio-video.php');
require_once src_path('Services/MetadataExtractor/part-pdf.php');

final class MetadataExtractor
{
    use MetadataExtractorPhotoTrait;
    use MetadataExtractorAudioVideoTrait;
    use MetadataExtractorPdfTrait;

    /**
     * Run a shell command with common tool paths prepended on Windows,
     * where Git Bash tools are not in the cmd.exe PATH.
     */
    private function shell(string $cmd): ?string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $extra = 'C:\\Program Files\\Git\\mingw64\\bin';
            return @shell_exec('cmd /c "set "PATH=%PATH%;' . $extra . '" && ' . $cmd . '"');
        }
        return @shell_exec($cmd);
    }

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
}
