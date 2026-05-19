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

- [ ] Open database/schema.sql (or create it if absent)
- [ ] Write CREATE TABLE students with columns: id INT AUTO_INCREMENT PK, ic_number VARCHAR(12) UNIQUE NOT NULL, matric_number VARCHAR(20) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, full_name VARCHAR(100) NOT NULL, phone VARCHAR(15), email VARCHAR(100), email_category ENUM('student','personal','work'), date_of_birth DATE, gender CHAR(1), state_of_birth VARCHAR(50), age INT, badge ENUM('Pendaftar','Pelajar','Aktif','Dedikasi','Cemerlang') DEFAULT 'Pendaftar', created_at DATETIME, updated_at DATETIME
- [ ] Add index on students(state_of_birth)
- [ ] Add index on students(email_category)
- [ ] Add index on students(gender)
- [ ] Add index on students(badge)

### Phase 2b: file_metadata Table

- [ ] Write CREATE TABLE file_metadata with columns: id INT AUTO_INCREMENT PK, student_id INT NOT NULL, file_type ENUM('photo','audio','pdf','video') NOT NULL, filename VARCHAR(255) NOT NULL, stored_filename VARCHAR(255) NOT NULL, file_path VARCHAR(500) NOT NULL, file_size INT UNSIGNED, mime_type VARCHAR(100), extracted_text LONGTEXT, upload_date DATETIME
- [ ] Add FK: file_metadata.student_id REFERENCES students(id) ON DELETE CASCADE
- [ ] Add composite index on file_metadata(student_id, file_type)
- [ ] Add FULLTEXT INDEX on file_metadata(extracted_text)

### Phase 2c: cbr_metadata Table

- [ ] Write CREATE TABLE cbr_metadata with columns: id INT AUTO_INCREMENT PK, file_id INT NOT NULL UNIQUE, photo_category VARCHAR(50), dominant_bg_color VARCHAR(30), bg_color_variance FLOAT, dominant_expression VARCHAR(30), expression_confidence FLOAT, audio_duration_sec INT UNSIGNED, audio_duration_tier ENUM('short','medium','long'), audio_bitrate INT UNSIGNED, video_resolution VARCHAR(20), video_resolution_tier ENUM('SD','HD','FHD','UHD'), video_duration_sec INT UNSIGNED
- [ ] Add FK: cbr_metadata.file_id REFERENCES file_metadata(id) ON DELETE CASCADE
- [ ] Add index on cbr_metadata(dominant_expression)
- [ ] Add index on cbr_metadata(photo_category)
- [ ] Add index on cbr_metadata(audio_duration_tier)
- [ ] Add index on cbr_metadata(video_resolution_tier)

### Phase 2d: tags and file_tags Tables

- [ ] Write CREATE TABLE tags with columns: id INT AUTO_INCREMENT PK, name VARCHAR(100) NOT NULL UNIQUE
- [ ] Write CREATE TABLE file_tags with columns: file_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY (file_id, tag_id)
- [ ] Add FK: file_tags.file_id REFERENCES file_metadata(id) ON DELETE CASCADE
- [ ] Add FK: file_tags.tag_id REFERENCES tags(id) ON DELETE CASCADE

### Phase 2e: registration_history Table

- [ ] Write CREATE TABLE registration_history with columns: id INT AUTO_INCREMENT PK, ic_number VARCHAR(12) NOT NULL, registered_at DATETIME NOT NULL, files_uploaded INT DEFAULT 0, badge_at_time VARCHAR(20), action ENUM('new','update') NOT NULL
- [ ] Add index on registration_history(ic_number)

### Phase 2f: Apply Schema

- [ ] Run schema.sql against the metamykad database: mysql -u root -p metamykad < database/schema.sql
- [ ] Verify all 6 tables exist with SHOW TABLES
- [ ] Verify FULLTEXT index exists on file_metadata: SHOW INDEX FROM file_metadata
- [ ] Verify FK constraints are in place with SHOW CREATE TABLE for each table that has FKs

## Progress Log

