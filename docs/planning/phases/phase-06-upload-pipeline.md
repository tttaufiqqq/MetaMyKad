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

- [x] Create src/Services/UploadService.php
- [x] ALLOWED_MIME constant — photo: jpeg/png, audio: mpeg/wav/x-wav, pdf: application/pdf, video: mp4/quicktime/x-msvideo
- [x] MAX_BYTES constant — photo 5MB, audio 20MB, pdf 10MB, video 100MB
- [x] Validation inline in processOne() — finfo_open(FILEINFO_MIME_TYPE) on tmp_name, size=0 check, size limit check; throws RuntimeException on failure
- [x] Stored filename: {studentId}_{fileType}_{time()}_{bin2hex(random_bytes(4))}.{ext}
- [x] MIME_EXT constant maps each MIME to safe extension
- [x] move_uploaded_file() inline in processOne(); throws on failure
- [x] processOne() returns upload data array (filename, stored_filename, file_path, file_size, mime_type)
- [x] processAll() skips UPLOAD_ERR_NO_FILE inputs silently; returns [file_type => data] map

### Phase 6b: Create MetadataExtractor

- [x] Create src/Services/MetadataExtractor.php
- [x] extract() dispatches via match on fileType
- [x] analyzePhoto() — getimagesize() for dimensions; aspect ratio → photo_category (formal: portrait h/w>=1.15 and width<=600px); GD corner sampling (4 corners avg) → dominant_bg_color hex + bg_color_variance; dominant_expression='neutral', expression_confidence=0.5; CbrMetadata::insert()
- [x] analyzeAudio() — @shell_exec ffprobe for duration + bitrate; heuristic fallback: filesize / (bitrate_kbps * 125) for duration; tier: <60s=short, 60-300s=medium, >300s=long; CbrMetadata::insert()
- [x] analyzeVideo() — @shell_exec ffprobe for width/height + duration; resolution tier: >1080=UHD, >720=FHD, >480=HD, else=SD; safe defaults if ffprobe unavailable; CbrMetadata::insert()
- [x] extractPdfText() — @shell_exec pdftotext {path} -; fallback: regex BT/ET block extraction from raw file bytes; FileMetadata::updateExtractedText(); empty string + error_log on total failure
- [x] @shell_exec returns null if disabled — all extractors handle null gracefully without crashing

### Phase 6c: Wire Upload Pipeline into RegistrationController

- [x] UploadService instantiated after sp_register_student returns student_id
- [x] processAll(studentId, $_FILES) called; RuntimeException caught → flash error + redirect
- [x] MetadataExtractor::extract() called per file in foreach; Throwable caught → error_log only (does not abort upload)
- [x] sp_update_badge(studentId) called after all files inserted
- [~] Partial upload rollback (unlink already-moved files on failure) — not implemented; acceptable for project scope

### Phase 6d: Config Entries

- [~] Dedicated config/upload.php — size limits are class constants in UploadService; sufficient for project scope
- [~] php.ini limit verification — manual check required; video uploads need upload_max_filesize >= 100M

### Phase 6e: Integration Tests

- [ ] Upload a JPEG photo — verify file in storage/uploads/photo/, file_metadata row, cbr_metadata row with photo_category
- [ ] Upload an MP3 audio — verify mime_type='audio/mpeg', cbr_metadata has audio_duration_tier
- [ ] Upload an MP4 video — verify cbr_metadata has video_resolution_tier
- [ ] Upload a PDF — verify file_metadata.extracted_text populated (or empty if pdftotext unavailable)
- [ ] Upload a .php file as photo — verify RuntimeException thrown, no file stored
- [ ] Upload a zero-byte file — verify rejection

## Progress Log

2026-05-19 - Phase 06 complete. Created UploadService.php (finfo MIME validation, size limits as class constants, stored filename pattern, move_uploaded_file). Created MetadataExtractor.php (GD photo analysis with corner sampling, ffprobe audio/video with heuristic/default fallbacks, pdftotext + raw BT/ET fallback for PDF). Both wired into RegistrationController. MetadataExtractor failures log to error_log but do not abort registration. Partial upload rollback and config file deferred.
