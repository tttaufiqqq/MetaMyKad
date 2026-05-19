# MetaMyKad
## MyKad-Driven Multimedia Student Registry with Intelligent Metadata Retrieval System

**Course:** BITP3353 — Multimedia Database

---

# 1. Project Title

**MetaMyKad**  
*MyKad-Driven Multimedia Student Registry with Intelligent Metadata Retrieval System*

---

# 2. Simple System Explanation

A student fills in their IC number and email, then optionally uploads four files — a photo, an audio recording, a PDF document, and a video.

The system automatically extracts information from the IC number such as:
- Date of birth
- Gender
- State of birth

The system also determines the category of the student email:
- Personal
- Student institutional
- Work

For uploaded multimedia files, the system extracts metadata and analyses content:
- Photo → formal/non-formal classification and facial expression detection
- PDF → searchable extracted text
- Audio → duration and bitrate analysis
- Video → duration and resolution analysis

The system stores structured data and multimedia metadata in a relational database and supports:
- Attribute-Based Retrieval (ABR)
- Text-Based Retrieval (TBR)
- Content-Based Retrieval (CBR)

Students also receive achievement badges depending on uploaded file completeness.

---

# 3. Problem Statement

Current student registration systems only store manually entered demographic information and do not analyse uploaded multimedia files meaningfully.

There is no unified platform capable of:
- Automatically deriving attributes from Malaysian IC numbers
- Classifying uploaded multimedia based on content features
- Supporting attribute-based, text-based, and content-based retrieval simultaneously

Lecturers and administrators cannot perform advanced multimedia queries such as:
- “Show all happy students with formal photos”
- “Find all PDFs containing the keyword algorithm”

This project solves that issue by creating a multimedia database system capable of intelligent metadata extraction and retrieval.

---

# 4. Objectives

1. Design and implement a multimedia relational database for structured and unstructured data.

2. Parse Malaysian IC numbers to derive:
   - Date of birth
   - Gender
   - State of birth

3. Parse email domains to determine:
   - Personal
   - Student
   - Work email category

4. Extract multimedia metadata automatically:
   - MIME type
   - File size
   - Upload date
   - File path

5. Implement Text-Based Retrieval using:
   - PDF text extraction
   - FULLTEXT search
   - User-defined tags

6. Implement Content-Based Retrieval using:
   - Photo analysis
   - Facial expression detection
   - Audio duration analysis
   - Video resolution classification

7. Store multimedia files on the server filesystem while storing only file paths in the database.

8. Implement a badge reward system based on uploaded file completeness.

9. Support ACID-compliant re-registration and audit logging.

10. Support safe multimedia deletion using transactions.

---

# 5. Entity Relationship Diagram (ERD)

```text
┌─────────────────────────────────┐
│            STUDENTS             │
├─────────────────────────────────┤
│ PK  id              INT         │
│     ic_number       VARCHAR(12) │
│     matric_number   VARCHAR(15) │
│     password        VARCHAR(255)│
│     full_name       VARCHAR(100)│
│     phone           VARCHAR(15) │
│     group_code      VARCHAR(10) │
│     life_motto      TEXT        │
│     email           VARCHAR(100)│
│     email_category  ENUM(       │
│       personal,student,work)    │
│     date_of_birth   DATE        │
│     gender          ENUM(M,F)   │
│     state_of_birth  VARCHAR(50) │
│     age             INT         │
│     badge           ENUM(       │
│       Pendaftar,Pelajar,        │
│       Aktif,Dedikasi,           │
│       Cemerlang)                │
│     created_at      DATETIME    │
│     updated_at      DATETIME    │
└──────────────┬──────────────────┘
               │ 1
               │
               │ M
┌──────────────▼──────────────────────────┐
│              FILE_METADATA              │
├─────────────────────────────────────────┤
│ PK  id              INT                 │
│ FK  student_id      INT                 │
│     file_type       ENUM(photo,audio,   │
│                     pdf,video)          │
│     filename        VARCHAR(255)        │
│     stored_filename VARCHAR(255)        │
│     file_path       VARCHAR(500)        │
│     file_size       BIGINT              │
│     mime_type       VARCHAR(100)        │
│     upload_date     DATETIME            │
│     extracted_text  LONGTEXT            │
└──────────────┬──────────────────────────┘
               │ 1
               │
               │ 1
┌──────────────▼──────────────────┐
│          CBR_METADATA           │
├─────────────────────────────────┤
│ PK  id               INT        │
│ FK  file_id          INT        │
└─────────────────────────────────┘
```

---

# 6. Data to Be Stored

## Structured Data
- Student identity information
- IC-derived attributes
- Email-derived attributes
- Badge levels
- Registration timestamps
- Tags

## Multimedia Data
- Photos
- Audio files
- PDF documents
- Videos

All multimedia files are stored on the server filesystem under organised directories.

Example structure:

```text
/uploads/
├── photo/
├── audio/
├── pdf/
└── video/
```

---

# 7. Multimedia Application

The system transforms uploaded multimedia into searchable metadata.

Examples:
- Photos become searchable by expression and formality
- PDFs become searchable by keyword
- Audio files become searchable by duration category
- Videos become searchable by quality tier

This demonstrates integration of:
- ABR
- TBR
- CBR

inside one multimedia database system.

---

# 8. Retrieval Mechanisms

## ABR — Attribute-Based Retrieval
Uses structured attributes such as:
- Gender
- State
- Email category
- File size
- MIME type

Example:
> Retrieve all female students from Melaka using student email accounts.

---

## TBR — Text-Based Retrieval
Uses textual information:
- Extracted PDF content
- User-defined tags

Example:
> Find all PDF files containing the keyword “algorithm”.

---

## CBR — Content-Based Retrieval
Uses analysed multimedia features:
- Facial expressions
- Photo formality
- Audio duration
- Video resolution

Example:
> Retrieve all students with happy facial expressions and Full HD videos.

---

# 9. ACID Implementation

## Atomicity
All re-registration operations execute within a transaction.

## Consistency
Foreign keys and ENUM constraints maintain valid data.

## Isolation
Row-level locking prevents concurrent IC conflicts.

## Durability
Committed data remains permanent using InnoDB logging.

---

# 10. Badge Reward System

| Uploaded Files | Badge |
|---|---|
| 0 | 🥉 Pendaftar |
| 1 | 🎓 Pelajar |
| 2 | 💡 Aktif |
| 3 | 🔥 Dedikasi |
| 4 | 🏆 Cemerlang |

---

# 11. Re-Registration Flow

The system supports safe re-registration by:
1. Logging old data into history
2. Deleting old metadata
3. Updating student information
4. Recomputing badge level
5. Committing transaction safely

---

# 12. Delete Button Placement

Delete buttons are placed inside the Metadata Dashboard.

Each deletion:
- Removes database records
- Deletes physical files using `unlink()`
- Executes inside a transaction

---

# 13. Key SQL Queries

## Query 1: ABR — State, Gender, Email Category with Aggregate

```sql
SELECT s.state_of_birth, s.gender, s.email_category,
       COUNT(fm.id) AS total_files,
       AVG(fm.file_size) AS avg_file_size_bytes
FROM students s
LEFT JOIN file_metadata fm ON s.id = fm.student_id
GROUP BY s.state_of_birth, s.gender, s.email_category
ORDER BY total_files DESC;
```

## Query 2: TBR — FULLTEXT Search on Extracted PDF Text

```sql
SELECT s.full_name, fm.filename,
       MATCH(fm.extracted_text)
       AGAINST('multimedia database' IN NATURAL LANGUAGE MODE) AS score
FROM file_metadata fm
JOIN students s ON fm.student_id = s.id
WHERE fm.file_type = 'pdf'
  AND MATCH(fm.extracted_text)
      AGAINST('multimedia database' IN NATURAL LANGUAGE MODE)
ORDER BY score DESC;
```

## Query 3: CBR + ABR — Formal Photo + Happy Expression + State

```sql
SELECT s.full_name, s.state_of_birth, s.email_category,
       c.dominant_expression, c.photo_category
FROM students s
JOIN file_metadata fm ON s.id = fm.student_id
JOIN cbr_metadata c ON fm.id = c.file_id
WHERE fm.file_type = 'photo'
  AND c.photo_category = 'formal'
  AND c.dominant_expression = 'happy'
  AND s.state_of_birth = 'Melaka';
```

## Query 4: Email Category vs Badge Achievement

```sql
SELECT s.email_category, s.badge,
       COUNT(*) AS total_students,
       AVG((SELECT COUNT(*) FROM file_metadata
            WHERE student_id = s.id)) AS avg_files_uploaded
FROM students s
GROUP BY s.email_category, s.badge
ORDER BY s.email_category, avg_files_uploaded DESC;
```

## Query 5: Registration History — Re-registrations Report

```sql
SELECT ic_number, COUNT(*) AS total_attempts,
       MIN(registered_at) AS first_registration,
       MAX(registered_at) AS latest_registration,
       MAX(files_uploaded) AS peak_files_uploaded
FROM registration_history
GROUP BY ic_number
HAVING total_attempts > 1
ORDER BY total_attempts DESC;
```

---

# 14. System Features Summary

| Feature | Implementation | Retrieval Type |
|---|---|---|
| IC number parsing | PHP string functions | ABR |
| Email category detection | PHP / `fn_GetEmailCategory()` | ABR |
| State of birth parsing | PHP / `fn_GetStateFromIC()` | ABR |
| File attribute extraction | PHP `finfo`, `stat()` | ABR |
| EXIF data from photo | PHP `exif_read_data()` | ABR |
| Server folder file storage | PHP `move_uploaded_file()` | — |
| PDF text extraction | `smalot/pdfparser` | TBR |
| Full-text keyword search | MySQL FULLTEXT index | TBR |
| User-defined file tags | Many-to-many tag table | TBR |
| Photo formal/non-formal | PHP GD pixel variance | CBR |
| Dominant background color | PHP GD color sampling | CBR |
| Facial expression detection | face-api.js (browser) | CBR |
| Audio duration and bitrate | getID3 PHP library | CBR |
| Video resolution and duration | getID3 / ffprobe | CBR |
| Badge reward system | `fn_ComputeBadge()` / `sp_UpdateBadge()` | — |
| Re-registration upsert | `sp_RegisterStudent()` + ACID | — |
| Per-file delete | `sp_DeleteFile()` + `unlink()` | — |
| Student summary view | `vw_student_summary` | — |
| Photo CBR view | `vw_cbr_photo_analysis` | — |
| File ABR report view | `vw_file_abr_report` | — |

---

# 15. Tagline

> “You upload the files. The system reads the story.”
