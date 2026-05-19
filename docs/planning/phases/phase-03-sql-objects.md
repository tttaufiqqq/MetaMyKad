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

- [x] Open (or create) database/functions.sql — file pre-existed
- [x] Write fn_GetEmailCategory — implemented as fn_email_category; checks student domains (%.edu.my, REGEXP student), personal (gmail/yahoo/outlook/hotmail), work default
- [x] Write fn_GetStateFromIC — implemented as fn_state_from_ic; full CASE mapping codes 01-16 + 21-59, 'Unknown' fallback; state names match PHP Student model
- [x] Write fn_ComputeBadge — implemented as fn_badge_by_file_count; uses COALESCE for null safety
- [x] Execute functions.sql against metamykad database — applied with no errors
- [x] Verify fn_GetEmailCategory — SELECT fn_email_category('test@student.utm.my') returned 'student'
- [x] Verify fn_GetStateFromIC — SELECT fn_state_from_ic('010203070001') returned 'Pulau Pinang' (code 07 = Pulau Pinang, matches PHP model)
- [x] Verify fn_ComputeBadge — SELECT fn_badge_by_file_count(4) returned 'Cemerlang'

### Phase 3b: Stored Procedures

- [x] Open (or create) database/procedures.sql — file pre-existed with 7 procedures
- [x] Write sp_register_student — full atomic procedure: SELECT FOR UPDATE to detect existing IC; re-reg: snapshot history + DELETE file_metadata (CASCADE removes cbr/tags) + UPDATE students; new: INSERT students + INSERT history; returns student_id; ROLLBACK on exception
- [x] Write sp_update_badge — uses fn_badge_by_file_count; updates students.badge and updated_at
- [x] Write sp_delete_file — SELECT student_id, DELETE file_metadata (CASCADE handles cbr/tags), CALL sp_update_badge; returns student_id; ROLLBACK on exception
- [x] Execute procedures.sql against metamykad database — applied with no errors; 10 procedures confirmed
- [~] Verify sp_register_student with live test data — deferred to integration test in Phase 05
- [~] Verify sp_update_badge with live test data — deferred
- [~] Verify sp_delete_file with live test data — deferred to integration test in Phase 09
- [~] Clean up test rows — deferred

### Phase 3c: Views

- [x] Open (or create) database/views.sql — file pre-existed with 4 comprehensive views
- [x] vw_student_profile_summary — supersedes planned vw_student_summary; joins vw_student_file_counts + registration_history subquery; richer than spec
- [x] vw_file_search_catalog — supersedes both vw_cbr_photo_analysis and vw_file_abr_report; joins file_metadata + students + cbr_metadata + file_tags + tags with GROUP_CONCAT for tag_list
- [x] vw_badge_distribution — bonus view for dashboard badge counts
- [x] Execute views.sql against metamykad database — applied with no errors
- [x] Verify all views present — confirmed: vw_badge_distribution, vw_file_search_catalog, vw_student_file_counts, vw_student_profile_summary

## Progress Log

2026-05-19 - Phase 03 complete. Added fn_state_from_ic (full 01-59 map) to functions.sql. Added sp_register_student, sp_update_badge, sp_delete_file to procedures.sql alongside 7 pre-existing procedures. Applied functions -> views -> procedures in order; all verified live. Note: view names differ from spec (vw_student_profile_summary, vw_file_search_catalog) — both are more comprehensive than the spec versions.
