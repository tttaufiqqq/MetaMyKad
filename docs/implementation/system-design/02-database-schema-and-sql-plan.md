# Database Schema And SQL Plan

**Parent:** [Implementation specs](./README.md)  
**Related:**
- [ERD](../../architecture/erd.md)
- [Search contracts](./06-retrieval-and-search-contracts.md)

Status: Draft  
Type: Implementation target

## Tables

- `students`
- `file_metadata`
- `cbr_metadata`
- `tags`
- `file_tags`
- `registration_history`

## Required Constraints

- unique key on `students.ic_number`
- unique key on `students.matric_number`
- foreign key `file_metadata.student_id -> students.id`
- unique key on `cbr_metadata.file_id`
- foreign keys on `file_tags.file_id` and `file_tags.tag_id`

## Recommended Indexes

- index `students(state_of_birth)`
- index `students(email_category)`
- index `students(gender)`
- index `students(badge)`
- composite index `file_metadata(student_id, file_type)`
- FULLTEXT index on `file_metadata.extracted_text`
- index `cbr_metadata(dominant_expression)`
- index `cbr_metadata(photo_category)`
- index `cbr_metadata(audio_duration_tier)`
- index `cbr_metadata(video_resolution_tier)`

## DDL Notes

- use `utf8mb4`
- use `DATETIME` for history and upload timestamps
- use `ENUM` only for stable small sets already listed in the proposal
- keep `file_path` relative to the project root so environments stay portable
- store `password` as a PHP `password_hash()` output — never plain text
- `matric_number` is used as the login username and as a component of stored filenames

## Query Principles

- use PDO prepared statements everywhere
- ABR queries should join only the tables required by active filters
- TBR should prefer `MATCH ... AGAINST` when FULLTEXT is available
- CBR queries should filter through `cbr_metadata` and join back to student context
