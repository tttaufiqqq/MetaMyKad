# Project Plan - Phase 9: Delete and Cleanup
Created: 2026-05-19
Source: docs/planning/backend-flow.md

## Instructions
- Auto-commit code after each completed todo item
- Update this file every 5 completed items (checkpoint save)
- Do not commit this plan file — it is your AI's working reference

## Architecture

```
POST /delete-file
  -> CSRFMiddleware (verify token)
  -> RegistrationController@deleteFile
       |
       +-- 1. Read file_id from POST, validate positive integer
       +-- 2. Load file_metadata row (get file_path and student_id)
       +-- 3. If row not found: flash error, redirect back
       +-- 4. START TRANSACTION (via sp_DeleteFile):
       |       DELETE cbr_metadata WHERE file_id = :file_id
       |       DELETE file_tags WHERE file_id = :file_id
       |       DELETE file_metadata WHERE id = :file_id
       |       CALL sp_UpdateBadge(student_id)
       |    COMMIT
       +-- 5. After commit: attempt unlink(absolute_path_to_file)
       +-- 6. If unlink fails: log path to error_log (do NOT re-open transaction)
       +-- 7. Flash result: success or partial success (DB deleted, file remains)
       +-- 8. Redirect to /student-detail?id={student_id}

Sequence rationale:
  DB delete happens BEFORE filesystem delete.
  If filesystem delete fails the DB record is already gone (preferable over
  the reverse: file gone but DB row still orphaned).
  Failed filesystem paths are logged for manual cleanup.
```

Delete safety rules:
  - Require CSRF token on every POST /delete-file request
  - Require confirm-dialog in the view before the form is submitted
  - Show separate feedback for DB outcome and disk outcome
  - Never delete a student row from this endpoint — files only

## Implementation Plan

### Phase 9a: Read Existing deleteFile Code

- [ ] Read src/Controllers/RegistrationController.php — locate deleteFile() method
- [ ] Determine what is already implemented and what is stub

### Phase 9b: Implement deleteFile() — Input Validation

- [ ] Read file_id from $_POST['file_id'], cast to int
- [ ] If file_id < 1 or not present: flash "Invalid request", redirect to /dashboard
- [ ] Verify CSRF token (should already be handled by CSRFMiddleware on POST /delete-file route)

### Phase 9c: Implement deleteFile() — Load File Record

- [ ] Call FileMetadata::findById(file_id) to get the full row
- [ ] If row is null: flash "File not found", redirect to /dashboard
- [ ] Extract student_id and file_path from the row
- [ ] Compute absolute path from file_path: project_root . '/' . file_path

### Phase 9d: Implement deleteFile() — Database Delete via sp_DeleteFile

- [ ] Call PDO: CALL sp_DeleteFile(:file_id) via prepared statement
- [ ] sp_DeleteFile internally: DELETE cbr_metadata, DELETE file_tags, DELETE file_metadata, CALL sp_UpdateBadge — all in one transaction
- [ ] If PDO throws an exception: flash "Database error, file not deleted", redirect back — do NOT attempt unlink
- [ ] Capture whether the DB delete succeeded

### Phase 9e: Implement deleteFile() — Filesystem Delete

- [ ] After successful DB transaction: attempt unlink($absolutePath)
- [ ] If unlink returns false or file does not exist: call error_log("File delete failed, manual cleanup needed: {$absolutePath}")
- [ ] Set a flag $diskDeletedSuccessfully = (unlink result)

### Phase 9f: Implement deleteFile() — Flash and Redirect

- [ ] If DB succeeded and disk succeeded: flash "File deleted successfully"
- [ ] If DB succeeded but disk failed: flash "File record removed, but the physical file could not be deleted. Manual cleanup may be needed."
- [ ] Redirect to /student-detail?id={student_id}

### Phase 9g: Confirm Dialog in Student Detail View

- [ ] In pages/student-detail.php, each delete button must trigger the confirm-dialog partial before submitting
- [ ] Confirm dialog text: "Delete this {file_type} file? This cannot be undone."
- [ ] The delete form must include: hidden file_id, CSRF token input, action=/delete-file, method=POST
- [ ] Do not use a plain anchor tag for delete — must be a form POST

### Phase 9h: History Page (pages/history.php)

- [ ] Read src/Views/pages/history.php in full
- [ ] Update config/routes.php: change GET /history to PageController@history (or create HistoryController if needed)
- [ ] Query all registration_history rows: SELECT rh.*, s.full_name FROM registration_history rh LEFT JOIN students s ON s.ic_number = rh.ic_number ORDER BY rh.registered_at DESC
- [ ] Render as a table: Full Name (or IC if name unavailable), Action, Registered At, Files Uploaded, Badge At Time
- [ ] Add pagination or limit to 50 rows if history is large

### Phase 9i: Integration Tests

- [ ] Register a student with 2 files. Delete one file via the UI — verify file_metadata row gone, cbr_metadata row gone, badge updated to 'Pelajar', physical file deleted from disk
- [ ] Delete the remaining file — verify badge updates to 'Pendaftar'
- [ ] Submit delete POST with invalid file_id=0 — verify flash error, no crash
- [ ] Submit delete POST without CSRF token — verify CSRF middleware blocks the request
- [ ] Simulate unlink failure (chmod 000 on file) — verify error_log entry, DB record still deleted, partial success flash shown
- [ ] Navigate to /history after registrations and deletions — verify all events are listed in order

## Progress Log

