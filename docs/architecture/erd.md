# Entity Relationship Diagram (ERD)
## MyKad-Driven Multimedia Student Registry with Intelligent Metadata Retrieval System

---

## Relationships Summary

```
STUDENTS            ||--o{ FILE_METADATA       : "uploads"
FILE_METADATA       ||--o| CBR_METADATA        : "has CBR"
FILE_METADATA       ||--o{ FILE_TAGS           : "tagged with"
TAGS                ||--o{ FILE_TAGS           : "used in"
STUDENTS            ||--o{ REGISTRATION_HISTORY: "logged in"
```

---

## Full ASCII Diagram

```
┌──────────────────────────────────────────────────────────┐
│                        STUDENTS                          │
├────────────────┬──────────────────┬──────────────────────┤
│ Column         │ Data Type        │ Description          │
├────────────────┼──────────────────┼──────────────────────┤
│ PK  id         │ INT              │ Unique identifier    │
│     ic_number  │ VARCHAR(12)      │ MyKad number, unique │
│  matric_number │ VARCHAR(15)      │ Matric no., unique   │
│     password   │ VARCHAR(255)     │ Hashed password      │
│     full_name  │ VARCHAR(100)     │ Student full name    │
│     phone      │ VARCHAR(15)      │ Contact number       │
│     email      │ VARCHAR(100)     │ Email address        │
│   group_code   │ VARCHAR(10)      │ Tutorial group e.g.  │
│                │                  │ GR01                 │
│   life_motto   │ TEXT             │ Free-text motto      │
│  email_category│ ENUM             │ personal/student/    │
│                │                  │ work ← email domain  │
│  date_of_birth │ DATE             │ ← parsed from IC     │
│     gender     │ ENUM(M,F)        │ ← last IC digit      │
│  state_of_birth│ VARCHAR(50)      │ ← PB code (7th-8th)  │
│     age        │ INT              │ ← computed from DOB  │
│     badge      │ ENUM             │ Pendaftar/Pelajar/   │
│                │                  │ Aktif/Dedikasi/      │
│                │                  │ Cemerlang            │
│     created_at │ DATETIME         │ Registration time    │
│     updated_at │ DATETIME         │ Last update time     │
└────────────────┴──────────────────┴──────────────────────┘
         │ 1
         │
         │ has many
         │ M
         ▼
┌──────────────────────────────────────────────────────────┐
│                      FILE_METADATA                       │
├────────────────┬──────────────────┬──────────────────────┤
│ Column         │ Data Type        │ Description          │
├────────────────┼──────────────────┼──────────────────────┤
│ PK  id         │ INT              │ Unique file record    │
│ FK  student_id │ INT              │ → STUDENTS.id        │
│     file_type  │ ENUM             │ photo/audio/pdf/video│
│     filename   │ VARCHAR(255)     │ Original filename    │
│ stored_filename│ VARCHAR(255)     │ Renamed on server    │
│     file_path  │ VARCHAR(500)     │ Relative server path │
│     file_size  │ BIGINT           │ Size in bytes [ABR]  │
│     mime_type  │ VARCHAR(100)     │ MIME type     [ABR]  │
│     upload_date│ DATETIME         │ Upload time   [ABR]  │
│ extracted_text │ LONGTEXT         │ PDF text      [TBR]  │
└────────────────┴──────────────────┴──────────────────────┘
         │ 1                          │ 1
         │                            │
         │ has one                    │ has many
         │ 1                          │ M
         ▼                            ▼
┌──────────────────────────────┐   ┌─────────────────────────┐
│         CBR_METADATA         │   │        FILE_TAGS         │
├──────────────┬───────────────┤   ├───────────┬─────────────┤
│ Column       │ Type          │   │ Column    │ Type        │
├──────────────┼───────────────┤   ├───────────┼─────────────┤
│ PK  id       │ INT           │   │ PK  id    │ INT         │
│ FK  file_id  │ INT           │   │ FK file_id│ INT → FM    │
│              │               │   │ FK  tag_id│ INT → TAGS  │
│  [PHOTO]     │               │   └───────────┴─────────────┘
│ photo_categ. │ ENUM          │             ▲ M
│              │ formal/       │             │
│              │ non_formal    │             │ used in
│ bg_variance  │ FLOAT   [CBR] │             │ M
│ dominant_bg_ │ VARCHAR(7)    │   ┌─────────┴───────────────┐
│   color      │ hex color     │   │          TAGS            │
│ dominant_    │ ENUM          │   ├───────────┬─────────────┤
│   expression │ happy/sad/    │   │ Column    │ Type        │
│              │ angry/neutral │   ├───────────┼─────────────┤
│              │ surprised/    │   │ PK  id    │ INT         │
│              │ fearful/      │   │  tag_name │ VARCHAR(50) │
│              │ disgusted     │   │           │        [TBR]│
│ expr_confid. │ FLOAT   [CBR] │   └───────────┴─────────────┘
│              │               │
│  [AUDIO]     │               │
│ audio_dur_   │ INT           │
│   sec        │               │
│ audio_dur_   │ ENUM          │
│   tier       │ short/medium/ │
│              │ long    [CBR] │
│ audio_bitrate│ INT           │
│              │               │
│  [VIDEO]     │               │
│ video_resol. │ VARCHAR(20)   │
│ video_resol_ │ ENUM          │
│   tier       │ SD/HD/FHD/UHD │
│              │         [CBR] │
│ video_dur_   │ INT           │
│   sec        │               │
└──────────────┴───────────────┘

┌──────────────────────────────────────────────────────────┐
│                   REGISTRATION_HISTORY                   │
├────────────────┬──────────────────┬──────────────────────┤
│ Column         │ Data Type        │ Description          │
├────────────────┼──────────────────┼──────────────────────┤
│ PK  id         │ INT              │ Unique record        │
│     ic_number  │ VARCHAR(12)      │ Links to STUDENTS    │
│  registered_at │ DATETIME         │ Timestamp of attempt │
│ files_uploaded │ INT              │ Snapshot file count  │
│  badge_at_time │ VARCHAR(20)      │ Badge snapshot       │
│     action     │ ENUM(new,update) │ Registration type    │
└────────────────┴──────────────────┴──────────────────────┘
         ▲ M
         │
         │ logged in
         │ 1
    STUDENTS.ic_number
```

---

## Retrieval Type Legend

```
[ABR]  Attribute-Based Retrieval  — structured metadata fields
[TBR]  Text-Based Retrieval       — extracted text, tags, keywords
[CBR]  Content-Based Retrieval    — features derived from file content
```

---

## Derived Attributes from IC Number

```
IC Format:  YYMMDD - PB - XXXG

YYMMDD  →  date_of_birth
PB      →  state_of_birth  (7th & 8th digit lookup)
G       →  gender           (odd = M, even = F)
```

## PB Code → State Mapping

```
01,21,22,23,24  →  Johor
02,25,26,27     →  Kedah
03,28,29        →  Kelantan
04,30           →  Melaka
05,31,59        →  Negeri Sembilan
06,32,33        →  Pahang
07,34,35        →  Penang
08,36,37,38,39  →  Perak
09,40           →  Perlis
10,41,42,43,44  →  Selangor
11,45,46        →  Terengganu
12,47,48,49     →  Sabah
13,50,51,52,53  →  Sarawak
14,54,55,56,57  →  Kuala Lumpur
15,58           →  Labuan
16              →  Putrajaya
```

---

## Badge System

```
Files uploaded  →  Badge
────────────────────────────
0               →  Pendaftar   (Bronze Medal)
1               →  Pelajar     (Graduate Cap)
2               →  Aktif       (Light Bulb)
3               →  Dedikasi    (Fire)
4               →  Cemerlang   (Trophy)
```

---

## Server File Storage Structure

```
/uploads/
├── photo/
│   └── {timestamp}_{matric}_photo.jpg
├── audio/
│   └── {timestamp}_{matric}_audio.mp3
├── pdf/
│   └── {timestamp}_{matric}_document.pdf
└── video/
    └── {timestamp}_{matric}_video.mp4
```

> Database stores only the file path — no binary BLOB storage.
