# Project Plan - Phase 6: Upload Pipeline
Created: 2026-05-19
Source: docs/planning/backend-flow.md

## Instructions
- Auto-commit code after each completed todo item
- Update this file every 5 completed items (checkpoint save)
- Do not commit this plan file — it is your AI's working reference

## Architecture

```
RegistrationController@store
  |
  +-- UploadService->processAll(student_id, $_FILES)
        |
        +-- for each file input (photo, audio, pdf, video):
              |
              +-- validateFile(file, expected_type)
              |     +-- finfo MIME check (never trust $_FILES['type'])
              |     +-- reject zero-byte files
              |     +-- enforce size limit from config
              |
              +-- generateStoredFilename(student_id, file_type, ext)
              |     pattern: {student_id}_{file_type}_{timestamp}_{random}.{ext}
              |
              +-- move_uploaded_file(tmp_path, storage/uploads/{type}/{stored_filename})
              |
              +-- FileMetadata::insert(row)
              |     columns: student_id, file_type, filename (original),
              |               stored_filename, file_path (relative), file_size, mime_type
              |
              +-- MetadataExtractor->extract(file_id, file_type, stored_path)
                    |
                    +-- photo  -> analyzPhoto()   -> CbrMetadata::insert()
                    +-- audio  -> analyzeAudio()  -> CbrMetadata::insert()
                    +-- video  -> analyzeVideo()  -> CbrMetadata::insert()
                    +-- pdf    -> extractText()   -> FileMetadata::updateExtractedText()

  +-- sp_UpdateBadge(student_id)   called once after all files processed
```

Allowed MIME types:
  photo: image/jpeg, image/png
  audio: audio/mpeg, audio/wav, audio/x-wav
  pdf:   application/pdf
  video: video/mp4, video/quicktime, video/x-msvideo

Storage paths:
  storage/uploads/photo/
  storage/uploads/audio/
  storage/uploads/pdf/
  storage/uploads/video/

## Implementation Plan

### Phase 6a: Create UploadService

- [ ] Create src/Services/UploadService.php
- [ ] Define allowed MIME map as a class constant: ['photo'=>['image/jpeg','image/png'], 'audio'=>['audio/mpeg','audio/wav','audio/x-wav'], 'pdf'=>['application/pdf'], 'video'=>['video/mp4','video/quicktime','video/x-msvideo']]
- [ ] Define per-type max size limits (read from config or hardcode defaults: photo 5MB, audio 20MB, pdf 10MB, video 100MB)
- [ ] Implement validateFile(array $fileEntry, string $expectedType): void — use finfo_open(FILEINFO_MIME_TYPE) on tmp_name, reject if MIME not in allowed list for expectedType, reject if size=0, reject if size exceeds limit, throw or return error on failure
- [ ] Implement generateStoredFilename(int $studentId, string $fileType, string $ext): string — returns "{studentId}_{fileType}_{time()}_{bin2hex(random_bytes(4))}.{ext}"
- [ ] Implement getExtension(string $mimeType): string — maps MIME to safe extension (e.g. image/jpeg -> jpg)
- [ ] Implement moveFile(string $tmpPath, string $destination): bool — calls move_uploaded_file, returns success bool
- [ ] Implement processOne(int $studentId, string $inputName, string $fileType, array $fileEntry): int — orchestrates validate + generateName + move + FileMetadata::insert, returns file_id
- [ ] Implement processAll(int $studentId, array $files): array — loops over the 4 known file inputs, calls processOne for each that is present and not empty, returns [file_type => file_id] map

### Phase 6b: Create MetadataExtractor

- [ ] Create src/Services/MetadataExtractor.php
- [ ] Implement extract(int $fileId, string $fileType, string $storedPath): void — dispatches to the correct analyzer based on fileType
- [ ] Implement analyzePhoto(int $fileId, string $path): void — use getimagesize() to get dimensions; compute a dominant background color heuristic (sample corners with imagecolorat or GD); set photo_category based on aspect ratio (portrait/landscape/square); set dominant_expression='neutral' and expression_confidence=0.5 as initial heuristic; call CbrMetadata::insert()
- [ ] Implement analyzeAudio(int $fileId, string $path): void — attempt to read ID3 tags or file headers to get duration_sec and bitrate; if extraction is unavailable set duration_sec=0 and bitrate=0; compute audio_duration_tier: <60s=short, 60-300s=medium, >300s=long; call CbrMetadata::insert()
- [ ] Implement analyzeVideo(int $fileId, string $path): void — attempt to read video metadata (ffprobe via shell_exec if available, else set defaults); set video_resolution (e.g. '1920x1080'), compute video_resolution_tier: <=480p=SD, <=720p=HD, <=1080p=FHD, >1080p=UHD; call CbrMetadata::insert()
- [ ] Implement extractText(int $fileId, string $path): void — attempt pdftotext via shell_exec('pdftotext ... -'), or use a PHP PDF reader if available; if extraction fails store empty string; call FileMetadata::updateExtractedText(fileId, text)
- [ ] Add extractText fallback: if shell_exec is disabled, log a warning to PHP error_log and store empty string — do not crash the upload

### Phase 6c: Wire Upload Pipeline into RegistrationController

- [ ] In RegistrationController@store, after sp_RegisterStudent succeeds, instantiate UploadService
- [ ] Call UploadService->processAll(student_id, $_FILES) to get the file_id map
- [ ] For each returned file_id, instantiate MetadataExtractor and call extract(file_id, file_type, stored_path)
- [ ] After all extractions complete, call sp_UpdateBadge(student_id) via PDO
- [ ] If processOne throws or returns false for any file: rollback the transaction, unlink any files already moved in this batch, flash error

### Phase 6d: Config Entries

- [ ] Add upload size limits to config/config.php (or a dedicated config/upload.php): photo_max_bytes, audio_max_bytes, pdf_max_bytes, video_max_bytes
- [ ] Verify php.ini upload_max_filesize and post_max_size are large enough for the video limit

### Phase 6e: Integration Tests

- [ ] Upload a JPEG photo — verify file exists in storage/uploads/photo/, file_metadata row inserted, cbr_metadata row inserted with photo_category set
- [ ] Upload an MP3 audio file — verify file_metadata row has mime_type='audio/mpeg', cbr_metadata has audio_duration_tier set
- [ ] Upload an MP4 video — verify cbr_metadata has video_resolution_tier set
- [ ] Upload a PDF — verify file_metadata.extracted_text is populated (or empty string if pdftotext unavailable)
- [ ] Attempt to upload a .php file as a photo — verify rejection, no file stored
- [ ] Attempt to upload a zero-byte file — verify rejection

## Progress Log

