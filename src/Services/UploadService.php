<?php

declare(strict_types=1);

namespace MetaMyKad\Services;

use RuntimeException;

final class UploadService
{
    private const ALLOWED_MIME = [
        'photo' => ['image/jpeg', 'image/png'],
        'audio' => ['audio/mpeg', 'audio/wav', 'audio/x-wav'],
        'pdf'   => ['application/pdf'],
        'video' => ['video/mp4', 'video/quicktime', 'video/x-msvideo'],
    ];

    private const MIME_EXT = [
        'image/jpeg'       => 'jpg',
        'image/png'        => 'png',
        'audio/mpeg'       => 'mp3',
        'audio/wav'        => 'wav',
        'audio/x-wav'      => 'wav',
        'application/pdf'  => 'pdf',
        'video/mp4'        => 'mp4',
        'video/quicktime'  => 'mov',
        'video/x-msvideo'  => 'avi',
    ];

    private const MAX_BYTES = [
        'photo' => 5  * 1024 * 1024,
        'audio' => 20 * 1024 * 1024,
        'pdf'   => 10 * 1024 * 1024,
        'video' => 100 * 1024 * 1024,
    ];

    /**
     * Process all four file inputs.
     * Returns an associative array keyed by file_type for each successfully uploaded file.
     * Missing or empty inputs are silently skipped.
     */
    public function processAll(int $studentId, array $files, string $studentName = ''): array
    {
        $nameSlug = $this->slugifyName($studentName ?: (string) $studentId);
        $results  = [];
        foreach (array_keys(self::ALLOWED_MIME) as $fileType) {
            $entry = $files[$fileType] ?? null;
            if ($entry === null || ($entry['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $results[$fileType] = $this->processOne($studentId, $fileType, $entry, $nameSlug);
        }
        return $results;
    }

    /**
     * Validate, move and describe one uploaded file.
     * Returns the data array ready for FileMetadata::insert().
     *
     * @throws RuntimeException on validation or move failure
     */
    public function processOne(int $studentId, string $fileType, array $entry, string $nameSlug = ''): array
    {
        if ($entry['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException("Upload error for {$fileType} (code {$entry['error']}).");
        }

        if ($entry['size'] === 0) {
            throw new RuntimeException("Uploaded {$fileType} file is empty.");
        }

        // MIME must be verified from the actual file, never trust $_FILES['type']
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = (string) finfo_file($finfo, $entry['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, self::ALLOWED_MIME[$fileType] ?? [], true)) {
            throw new RuntimeException("Invalid file type for {$fileType}: got {$mime}.");
        }

        if ($entry['size'] > self::MAX_BYTES[$fileType]) {
            $limitMb = self::MAX_BYTES[$fileType] / (1024 * 1024);
            throw new RuntimeException("File too large for {$fileType}. Limit is {$limitMb} MB.");
        }

        $slug = $nameSlug ?: $this->slugifyName((string) $studentId);
        $ext  = self::MIME_EXT[$mime];

        $storedFilename = "{$slug}_{$fileType}_" . time() . '_' . bin2hex(random_bytes(4)) . ".{$ext}";
        $displayName    = "{$slug}_{$fileType}.{$ext}";
        $relativePath   = "storage/uploads/{$fileType}/{$storedFilename}";
        $absolutePath   = base_path($relativePath);

        if (!move_uploaded_file($entry['tmp_name'], $absolutePath)) {
            throw new RuntimeException("Failed to store uploaded {$fileType} file.");
        }

        return [
            'file_type'       => $fileType,
            'filename'        => $displayName,
            'stored_filename' => $storedFilename,
            'file_path'       => $relativePath,
            'file_size'       => $entry['size'],
            'mime_type'       => $mime,
        ];
    }

    /**
     * Convert a full name into a lowercase underscore slug safe for filenames.
     * e.g. "Muhammad Taufiq bin Mohd Arifin" → "muhammad_taufiq_bin_mohd_arifin"
     */
    private function slugifyName(string $name): string
    {
        $slug = mb_strtolower(trim($name));
        $slug = (string) preg_replace('/[^a-z0-9]+/', '_', $slug);
        return trim(mb_substr($slug, 0, 50), '_');
    }
}
