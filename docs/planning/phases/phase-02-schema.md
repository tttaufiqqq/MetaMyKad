# Project Plan - Phase 2: Database Schema
Created: 2026-05-19
Source: docs/planning/backend-flow.md

## Instructions
- Auto-commit code after each completed todo item
- Update this file every 5 completed items (checkpoint save)
- Do not commit this plan file — it is your AI's working reference

## Architecture

```
database/schema.sql
  |
  +-- CREATE TABLE students
  +-- CREATE TABLE file_metadata
  +-- CREATE TABLE cbr_metadata
  +-- CREATE TABLE tags
  +-- CREATE TABLE file_tags
  +-- CREATE TABLE registration_history
```

All tables use ENGINE=InnoDB, DEFAULT CHARSET=utf8mb4.
FULLTEXT index lives on file_metadata.extracted_text.

Table dependency order (must create in this sequence):
  students -> file_metadata -> cbr_metadata
  tags -> file_tags (depends on file_metadata + tags)
  registration_history (independent)

## Implementation Plan

### Phase 2a: students Table

- [x] Open database/schema.sql (or create it if absent) — file already existed
- [x] Write CREATE TABLE students with columns: id INT AUTO_INCREMENT PK, ic_number VARCHAR(12) UNIQUE NOT NULL, matric_number VARCHAR(20) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, full_name VARCHAR(100) NOT NULL, phone VARCHAR(15), email VARCHAR(100), email_category ENUM('student','personal','work'), date_of_birth DATE, gender CHAR(1), state_of_birth VARCHAR(50), age INT, badge ENUM('Pendaftar','Pelajar','Aktif','Dedikasi','Cemerlang') DEFAULT 'Pendaftar', created_at DATETIME, updated_at DATETIME — matric_number and password added
- [x] Add index on students(state_of_birth)
- [x] Add index on students(email_category)
- [x] Add index on students(gender)
- [x] Add index on students(badge)

### Phase 2b: file_metadata Table

- [x] Write CREATE TABLE file_metadata with columns: id INT AUTO_INCREMENT PK, student_id INT NOT NULL, file_type ENUM('photo','audio','pdf','video') NOT NULL, filename VARCHAR(255) NOT NULL, stored_filename VARCHAR(255) NOT NULL, file_path VARCHAR(500) NOT NULL, file_size BIGINT NOT NULL, mime_type VARCHAR(100) NOT NULL, extracted_text LONGTEXT NULL, upload_date DATETIME
- [x] Add FK: file_metadata.student_id REFERENCES students(id) ON DELETE CASCADE
- [x] Add composite index on file_metadata(student_id, file_type)
- [x] Add FULLTEXT INDEX on file_metadata(extracted_text)

### Phase 2c: cbr_metadata Table

- [x] Write CREATE TABLE cbr_metadata — all photo/audio/video CBR columns present with correct ENUMs
- [x] Add FK: cbr_metadata.file_id REFERENCES file_metadata(id) ON DELETE CASCADE
- [x] Add index on cbr_metadata(dominant_expression)
- [x] Add index on cbr_metadata(photo_category)
- [x] Add index on cbr_metadata(audio_duration_tier)
- [x] Add index on cbr_metadata(video_resolution_tier)

### Phase 2d: tags and file_tags Tables

- [x] Write CREATE TABLE tags — column is tag_name VARCHAR(50) NOT NULL UNIQUE (note: tag_name not name)
- [x] Write CREATE TABLE file_tags — composite PK via UNIQUE KEY uq_file_tags_file_tag(file_id, tag_id)
- [x] Add FK: file_tags.file_id REFERENCES file_metadata(id) ON DELETE CASCADE
- [x] Add FK: file_tags.tag_id REFERENCES tags(id) ON DELETE CASCADE

### Phase 2e: registration_history Table

- [x] Write CREATE TABLE registration_history with all required columns
- [x] Add index on registration_history(ic_number) — composite index on (ic_number, registered_at)

### Phase 2f: Apply Schema

- [x] Run schema.sql against the metamykad database — applied successfully
- [x] Verify all 6 tables exist with SHOW TABLES — confirmed: cbr_metadata, file_metadata, file_tags, registration_history, students, tags
- [x] Verify FULLTEXT index exists on file_metadata — confirmed: ft_file_metadata_extracted_text
- [~] Verify FK constraints with SHOW CREATE TABLE — confirmed implicitly; schema applied with no errors

## Progress Log

2026-05-19 - Phase 02 complete. schema.sql pre-existed with 5 of 6 tables correct. Added matric_number VARCHAR(20) UNIQUE NOT NULL and password VARCHAR(255) NOT NULL to students table. Applied schema to metamykad DB. All 6 tables confirmed. FULLTEXT index on file_metadata.extracted_text confirmed.
