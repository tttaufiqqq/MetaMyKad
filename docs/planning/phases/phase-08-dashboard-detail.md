# Project Plan - Phase 8: Dashboard and Student Detail
Created: 2026-05-19
Source: docs/planning/backend-flow.md

## Instructions
- Auto-commit code after each completed todo item
- Update this file every 5 completed items (checkpoint save)
- Do not commit this plan file — it is your AI's working reference

## Architecture

```
GET /dashboard
  -> DashboardController@index  (new, or extend PageController)
       |
       +-- total students:         SELECT COUNT(*) FROM students
       +-- total files by type:    SELECT file_type, COUNT(*) FROM file_metadata GROUP BY file_type
       +-- badge distribution:     SELECT badge, COUNT(*) FROM students GROUP BY badge
       +-- recent registrations:   SELECT * FROM vw_student_summary ORDER BY created_at DESC LIMIT 10
       |
       -> render pages/dashboard.php

GET /student-detail?id={student_id}
  -> StudentController@show  (new, or extend PageController)
       |
       +-- student core data:   SELECT * FROM vw_student_summary WHERE id = :id
       +-- files list:          SELECT * FROM file_metadata WHERE student_id = :id
       +-- cbr data per file:   SELECT * FROM cbr_metadata WHERE file_id IN (...)
       +-- tags per file:       SELECT t.name FROM tags t JOIN file_tags ft ON ft.tag_id=t.id WHERE ft.file_id = :file_id
       +-- history:             SELECT * FROM registration_history WHERE ic_number = :ic ORDER BY registered_at DESC
       |
       -> render pages/student-detail.php
```

## Implementation Plan

### Phase 8a: Create DashboardController

- [ ] Create src/Controllers/DashboardController.php extending BaseController
- [ ] Update config/routes.php: change GET /dashboard route from PageController@dashboard to DashboardController@index
- [ ] Implement index(): void
- [ ] Query total students count: SELECT COUNT(*) FROM students
- [ ] Query file count by type: SELECT file_type, COUNT(*) AS cnt FROM file_metadata GROUP BY file_type — build an associative array [photo=>n, audio=>n, pdf=>n, video=>n] with 0 for any missing type
- [ ] Query badge distribution: SELECT badge, COUNT(*) AS cnt FROM students GROUP BY badge
- [ ] Query recent 10 registrations: SELECT id, full_name, badge, total_files, created_at FROM vw_student_summary ORDER BY created_at DESC LIMIT 10
- [ ] Pass all four data sets to the dashboard view

### Phase 8b: Build Dashboard View (pages/dashboard.php)

- [ ] Display total student count in a summary card
- [ ] Display file counts by type (photo, audio, pdf, video) — one card or row per type
- [ ] Display badge distribution as a table or simple list (badge label + count)
- [ ] Display recent registrations as a table: Full Name, Badge, Files Uploaded, Registered At, link to detail
- [ ] Ensure the view handles 0 students gracefully (show "No students registered yet")

### Phase 8c: Create StudentController

- [ ] Create src/Controllers/StudentController.php extending BaseController
- [ ] Update config/routes.php: change GET /student-detail route from PageController@studentDetail to StudentController@show
- [ ] Implement show(): void — read id from $_GET['id'], validate it is a positive integer
- [ ] If id missing or invalid: flash error and redirect to /dashboard
- [ ] Query student row: SELECT * FROM vw_student_summary WHERE id = :id — if null: render 404 view
- [ ] Query files: SELECT * FROM file_metadata WHERE student_id = :id ORDER BY upload_date DESC
- [ ] For each file, query cbr row: SELECT * FROM cbr_metadata WHERE file_id = :file_id
- [ ] For each file, query tags: SELECT t.name FROM tags t JOIN file_tags ft ON ft.tag_id=t.id WHERE ft.file_id = :file_id
- [ ] Query history: SELECT * FROM registration_history WHERE ic_number = :ic ORDER BY registered_at DESC
- [ ] Pass student, files (with cbr + tags nested), and history to view

### Phase 8d: Build Student Detail View (pages/student-detail.php)

- [ ] Display student summary card: full name, IC, gender, state of birth, date of birth, age, email, email category, badge, total files, created_at
- [ ] Display each file in a section: file_type icon or label, original filename, file size, MIME type, upload date
- [ ] For photo files: show cbr fields — photo_category, dominant_expression, expression_confidence, dominant_bg_color, bg_color_variance
- [ ] For audio files: show cbr fields — audio_duration_sec, audio_duration_tier, audio_bitrate
- [ ] For video files: show cbr fields — video_resolution, video_resolution_tier, video_duration_sec
- [ ] For PDF files: show extracted_text (truncated with "show more" if over 300 chars) or "No text extracted"
- [ ] For each file: display tags as pills/badges (if any)
- [ ] For each file: include a delete button that triggers the confirm-dialog partial; delete POSTs to /delete-file with file_id and CSRF token
- [ ] Display registration history table: action, registered_at, files_uploaded, badge_at_time
- [ ] Add a "Re-register" button linking to /re-register with IC pre-filled

### Phase 8e: Integration Tests

- [ ] Navigate to /dashboard with 0 students — verify graceful empty state
- [ ] Register a student, navigate to /dashboard — verify counts update correctly
- [ ] Navigate to /student-detail?id=1 — verify all sections render (files, cbr, history)
- [ ] Navigate to /student-detail?id=9999 — verify 404 renders, not a PHP error
- [ ] Navigate to /student-detail with no id param — verify redirect to /dashboard with error flash

## Progress Log

