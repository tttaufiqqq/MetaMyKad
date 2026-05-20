<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Core\Auth;
use MetaMyKad\Models\FileMetadata;
use MetaMyKad\Models\Tag;

final class TagController extends BaseController
{
    public function add(): void
    {
        if (!Auth::check()) {
            $this->json(['error' => 'Login required.'], 401);
        }

        $fileId  = (int) ($_POST['file_id'] ?? 0);
        $tagName = trim(mb_strtolower((string) ($_POST['tag_name'] ?? '')));

        if ($fileId < 1) {
            $this->json(['error' => 'Invalid file.'], 422);
        }

        if ($tagName === '' || mb_strlen($tagName) > 32) {
            $this->json(['error' => 'Tag must be 1–32 characters.'], 422);
        }

        if (!preg_match('/^[a-z0-9][a-z0-9 \-]*$/u', $tagName)) {
            $this->json(['error' => 'Only letters, numbers, spaces and hyphens allowed.'], 422);
        }

        $file = (new FileMetadata())->find($fileId);
        if ($file === false) {
            $this->json(['error' => 'File not found.'], 404);
        }

        $tagModel     = new Tag();
        $existingTags = $tagModel->findByFileId($fileId);

        // Idempotent: already attached → return ok
        foreach ($existingTags as $t) {
            if ($t['tag_name'] === $tagName) {
                $this->json(['ok' => true, 'tag' => $tagName]);
            }
        }

        if (count($existingTags) >= 10) {
            $this->json(['error' => 'Maximum 10 tags per file.'], 422);
        }

        $tagId = $tagModel->findOrCreate($tagName);
        $tagModel->attachToFile($fileId, $tagId);

        $this->json(['ok' => true, 'tag' => $tagName]);
    }

    public function remove(): void
    {
        if (!Auth::check()) {
            $this->json(['error' => 'Login required.'], 401);
        }

        $fileId  = (int) ($_POST['file_id'] ?? 0);
        $tagName = trim(mb_strtolower((string) ($_POST['tag_name'] ?? '')));

        if ($fileId < 1) {
            $this->json(['error' => 'Invalid file.'], 422);
        }

        if ($tagName === '') {
            $this->json(['error' => 'Tag name required.'], 422);
        }

        $file = (new FileMetadata())->find($fileId);
        if ($file === false) {
            $this->json(['error' => 'File not found.'], 404);
        }

        (new Tag())->detachByName($fileId, $tagName);

        $this->json(['ok' => true]);
    }
}
