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

- [ ] Read src/Models/Student.php in full
- [ ] Locate deriveFromIc() — verify it handles the year prefix rule: if YY > current 2-digit year then prefix 19, else prefix 20
- [ ] Verify deriveFromIc() extracts state code from digits 6-7 (positions 6 and 7, 0-indexed)
- [ ] Fix state code map in deriveFromIc() to include ALL codes: primary codes 01-16 AND secondary codes 21-59 (full list from fn_GetStateFromIC spec)
- [ ] Verify deriveFromIc() derives gender from last digit: odd = M, even = F
- [ ] Verify deriveFromIc() computes age from date_of_birth at current date
- [ ] Read classifyEmail() — verify it checks 'student' for %.edu.my / contains 'student' keyword, checks 'personal' for gmail/yahoo/outlook/hotmail, defaults to 'work'
- [ ] Ensure classifyEmail() checks domain-based rules (not just substring 'student') to avoid false positives
- [ ] Add/verify Student::findByIc(string $ic): ?array — returns students row or null
- [ ] Add/verify Student::findById(int $id): ?array — returns students row or null

### Phase 4b: Create FileMetadata.php

- [ ] Create src/Models/FileMetadata.php extending BaseModel
- [ ] Implement insert(array $data): int — inserts one file_metadata row, returns new id
- [ ] Implement findByStudentId(int $studentId): array — returns all file_metadata rows for that student
- [ ] Implement findById(int $id): ?array — returns one row or null
- [ ] Implement deleteById(int $id): void — deletes the file_metadata row (cascade handles child rows)
- [ ] Implement findByStudentIdAndType(int $studentId, string $type): ?array — returns first matching row for that file_type

### Phase 4c: Create CbrMetadata.php

- [ ] Create src/Models/CbrMetadata.php extending BaseModel
- [ ] Implement insert(array $data): int — inserts one cbr_metadata row, returns new id
- [ ] Implement findByFileId(int $fileId): ?array — returns cbr_metadata row for that file or null
- [ ] Implement deleteByFileId(int $fileId): void — deletes cbr_metadata row (used when overwriting a file)

### Phase 4d: Create Tag.php

- [ ] Create src/Models/Tag.php extending BaseModel
- [ ] Implement findOrCreate(string $name): int — returns existing tag id or inserts new tag, returns id
- [ ] Implement attachToFile(int $fileId, int $tagId): void — inserts file_tags row (ignore duplicate)
- [ ] Implement findByFileId(int $fileId): array — returns all tag names for that file
- [ ] Implement detachAllFromFile(int $fileId): void — deletes all file_tags rows for that file

### Phase 4e: Verify RegistrationHistory.php

- [ ] Read src/Models/RegistrationHistory.php in full
- [ ] Verify it has insert(array $data): int
- [ ] Verify it has findByIc(string $ic): array — returns all history rows for that IC ordered by registered_at DESC
- [ ] Add any missing methods
- [ ] Verify column names match the schema: ic_number, registered_at, files_uploaded, badge_at_time, action

### Phase 4f: Verify View Wrapper Models

- [ ] Read src/Models/StudentProfileSummaryView.php — confirm it queries vw_student_summary not the raw table
- [ ] Read src/Models/FileSearchCatalogView.php — confirm it queries vw_file_abr_report
- [ ] Verify StudentProfileSummaryView has a method to return all rows and one to find by student id
- [ ] Verify FileSearchCatalogView has a method that accepts filter params and builds a safe dynamic WHERE clause

## Progress Log

