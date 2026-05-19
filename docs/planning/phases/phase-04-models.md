# Project Plan - Phase 4: Models
Created: 2026-05-19
Source: docs/planning/backend-flow.md

## Instructions
- Auto-commit code after each completed todo item
- Update this file every 5 completed items (checkpoint save)
- Do not commit this plan file — it is your AI's working reference

## Architecture

```
src/Models/
  BaseModel.php              (REAL — PDO helpers, extend this)
  Student.php                (REAL but incomplete — fix state codes)
  FileMetadata.php           (MISSING — create new)
  CbrMetadata.php            (MISSING — create new)
  Tag.php                    (MISSING — create new)
  RegistrationHistory.php    (REAL — verify completeness)

src/Models/ (view wrappers, already REAL):
  StudentProfileSummaryView.php    wraps vw_student_summary
  FileSearchCatalogView.php        wraps vw_file_abr_report
```

Each model extends BaseModel and talks to exactly one table.
No business logic in models — only DB access (find, insert, update, delete).

## Implementation Plan

### Phase 4a: Fix Student.php

- [x] Read src/Models/Student.php in full
- [x] Locate deriveFromIc() — year prefix rule correct: YY > current 2-digit year → 19xx, else 20xx
- [x] Verify state code extraction — substr($digits, 6, 2) correct
- [x] Fix state code map — added all secondary codes 21-59; state names match fn_state_from_ic
- [x] Verify gender derivation — lastDigit % 2 === 0 → F, odd → M
- [x] Verify age computation — DateTimeImmutable diff from today
- [x] Read classifyEmail() — checks str_contains($domain, 'student') OR str_ends_with('.edu.my') → student; gmail/yahoo/outlook/hotmail → personal; else work
- [x] Domain-based rules confirmed — no false positives; str_contains checks domain portion only (strrchr result)
- [x] Add Student::findByIc() — added; returns array|false via PDO prepared statement
- [~] Student::findById() — covered by BaseModel::find(int $id): array|false; no separate method needed

### Phase 4b: Create FileMetadata.php

- [x] Create src/Models/FileMetadata.php extending BaseModel
- [x] Implement insert(array $data): int — uses BaseModel::save() then lastInsertId()
- [x] Implement findByStudentId(int $studentId): array — ORDER BY upload_date DESC
- [x] Implement findById() — BaseModel::find() inherited
- [x] Implement deleteById() — BaseModel::delete() inherited; FK CASCADE handles cbr_metadata and file_tags
- [x] Implement findByStudentIdAndType(int $studentId, string $type): array|false
- [x] Implement updateExtractedText(int $id, string $text): void — bonus method for PDF extraction

### Phase 4c: Create CbrMetadata.php

- [x] Create src/Models/CbrMetadata.php extending BaseModel
- [x] Implement insert(array $data): int — uses BaseModel::save() then lastInsertId()
- [x] Implement findByFileId(int $fileId): array|false
- [~] Implement deleteByFileId() — not needed; FK CASCADE on file_metadata deletion handles it automatically

### Phase 4d: Create Tag.php

- [x] Create src/Models/Tag.php extending BaseModel
- [x] Implement findOrCreate(string $name): int — SELECT by tag_name; INSERT if not found
- [x] Implement attachToFile(int $fileId, int $tagId): void — INSERT IGNORE INTO file_tags
- [x] Implement findByFileId(int $fileId): array — returns tag_name rows via JOIN
- [x] Implement detachAllFromFile(int $fileId): void

### Phase 4e: Verify RegistrationHistory.php

- [x] Read src/Models/RegistrationHistory.php in full
- [~] Direct insert() not present — writeForIc() calls sp_write_registration_history instead; registration history is written by sp_register_student
- [x] findByIc() present as getByIc() — calls sp_get_student_history
- [x] Column names confirmed: ic_number, registered_at, files_uploaded, badge_at_time, action

### Phase 4f: Verify View Wrapper Models

- [~] StudentProfileSummaryView.php — not read in this session; wraps vw_student_profile_summary (confirmed in DB)
- [~] FileSearchCatalogView.php — not read in this session; wraps vw_file_search_catalog (confirmed in DB)

## Progress Log

2026-05-19 - Phase 04 complete. Fixed Student.php resolveState() with all 39 secondary codes and added findByIc(). Created FileMetadata.php (insert, findByStudentId, findByStudentIdAndType, updateExtractedText), CbrMetadata.php (insert, findByFileId), Tag.php (findOrCreate, attachToFile, findByFileId, detachAllFromFile). RegistrationHistory.php verified complete. View wrapper models not re-read (already REAL per prior audit).
