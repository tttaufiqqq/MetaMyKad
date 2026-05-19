# Project Plan - Phase 3: SQL Objects
Created: 2026-05-19
Source: docs/planning/backend-flow.md

## Instructions
- Auto-commit code after each completed todo item
- Update this file every 5 completed items (checkpoint save)
- Do not commit this plan file — it is your AI's working reference

## Architecture

```
database/
  functions.sql     -- deterministic scalar functions (no side effects)
  procedures.sql    -- transactional procedures (INSERT/UPDATE/DELETE)
  views.sql         -- read-only virtual tables used by queries

Execution order: functions.sql -> procedures.sql -> views.sql
(sp_UpdateBadge uses fn_ComputeBadge, so functions must exist first)
```

Call graph:
```
sp_RegisterStudent
  -> INSERT/UPDATE students
  -> INSERT registration_history

sp_DeleteFile
  -> DELETE cbr_metadata, file_tags, file_metadata
  -> CALL sp_UpdateBadge

sp_UpdateBadge
  -> COUNT file_metadata rows
  -> UPDATE students.badge (inline CASE, not fn_ComputeBadge)
```

## Implementation Plan

### Phase 3a: Functions

- [ ] Open (or create) database/functions.sql
- [ ] Write fn_GetEmailCategory(p_email VARCHAR(100)) RETURNS VARCHAR(10) DETERMINISTIC — returns 'personal' for gmail/yahoo/hotmail/outlook/icloud/live/me, 'student' for %.edu.my / %.edu / %.ac.my / %.ac.uk domains, 'work' for everything else
- [ ] Write fn_GetStateFromIC(p_ic VARCHAR(12)) RETURNS VARCHAR(50) DETERMINISTIC — reads SUBSTRING(p_ic,7,2) and returns state name using full CASE mapping for codes 01-59, returns 'Unknown' for unrecognised codes (cover all codes listed in spec: 01-16 primary, 21-59 secondary)
- [ ] Write fn_ComputeBadge(p_count INT) RETURNS VARCHAR(20) DETERMINISTIC — CASE: 0->Pendaftar, 1->Pelajar, 2->Aktif, 3->Dedikasi, >=4->Cemerlang
- [ ] Execute functions.sql against metamykad database
- [ ] Verify fn_GetEmailCategory: SELECT fn_GetEmailCategory('a@gmail.com') should return 'personal'
- [ ] Verify fn_GetStateFromIC: SELECT fn_GetStateFromIC('010203070001') should return 'Perak' (code 07)
- [ ] Verify fn_ComputeBadge: SELECT fn_ComputeBadge(4) should return 'Cemerlang'

### Phase 3b: Stored Procedures

- [ ] Open (or create) database/procedures.sql
- [ ] Write sp_RegisterStudent — accepts p_ic, p_name, p_phone, p_email, p_email_cat, p_dob, p_gender, p_state, p_age; uses SELECT ... FOR UPDATE to detect existing student; if found: snapshot to registration_history (action='update'), DELETE cbr_metadata/file_tags/file_metadata, UPDATE students; if not found: INSERT students with badge='Pendaftar', INSERT registration_history (action='new'); wrapped in START TRANSACTION / COMMIT / EXIT HANDLER ROLLBACK
- [ ] Write sp_UpdateBadge(p_student_id INT) — COUNT file_metadata WHERE student_id=p_student_id, apply CASE to derive badge string, UPDATE students SET badge=...
- [ ] Write sp_DeleteFile(p_file_id INT) — SELECT student_id, DELETE cbr_metadata, DELETE file_tags, DELETE file_metadata, CALL sp_UpdateBadge(v_student_id); wrapped in START TRANSACTION / COMMIT / EXIT HANDLER ROLLBACK
- [ ] Execute procedures.sql against metamykad database
- [ ] Verify sp_RegisterStudent: CALL sp_RegisterStudent with a test IC and check students row is inserted
- [ ] Verify sp_UpdateBadge: insert a fake file_metadata row, CALL sp_UpdateBadge, check students.badge changed
- [ ] Verify sp_DeleteFile: CALL sp_DeleteFile on the test row, check row is gone and badge updated
- [ ] Clean up test rows inserted during verification

### Phase 3c: Views

- [ ] Open (or create) database/views.sql
- [ ] Write vw_student_summary — SELECT s.id, s.ic_number, s.full_name, s.gender, s.state_of_birth, s.date_of_birth, s.age, s.email, s.email_category, s.badge, COUNT(fm.id) AS total_files, s.created_at, s.updated_at FROM students s LEFT JOIN file_metadata fm ON s.id=fm.student_id GROUP BY s.id
- [ ] Write vw_cbr_photo_analysis — SELECT s.full_name, s.state_of_birth, s.gender, s.email_category, s.badge, c.photo_category, c.dominant_bg_color, c.bg_color_variance, c.dominant_expression, c.expression_confidence FROM students s JOIN file_metadata fm ON s.id=fm.student_id JOIN cbr_metadata c ON fm.id=c.file_id WHERE fm.file_type='photo'
- [ ] Write vw_file_abr_report — SELECT s.full_name, s.ic_number, s.gender, s.state_of_birth, s.email_category, fm.file_type, fm.filename, fm.file_size, fm.mime_type, fm.upload_date FROM students s JOIN file_metadata fm ON s.id=fm.student_id
- [ ] Execute views.sql against metamykad database
- [ ] Verify vw_student_summary: SELECT * FROM vw_student_summary LIMIT 1 (or confirm 0 rows if DB is empty)
- [ ] Verify vw_cbr_photo_analysis: DESCRIBE vw_cbr_photo_analysis to confirm all expected columns are present
- [ ] Verify vw_file_abr_report: DESCRIBE vw_file_abr_report to confirm all expected columns are present

## Progress Log

