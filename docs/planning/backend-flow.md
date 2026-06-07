# Backend Implementation Flow

```
┌─────────────────────────────────────────────────────────┐
│  FIX ENTRY POINT                                        │
│  public/index.php                                       │
│  + vendor/autoload.php                                  │
└───────────────────────────┬─────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│  SCHEMA                                                 │
│                                                         │
│  schema.sql                                             │
│    students                                             │
│    file_metadata                                        │
│    cbr_metadata                                         │
│    tags + file_tags                                     │
│    registration_history                                 │
└───────────────────────────┬─────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│  SQL OBJECTS                                            │
│                                                         │
│  functions.sql          views.sql                       │
│    fn_GetEmailCategory    vw_student_summary            │
│    fn_GetStateFromIC      vw_cbr_photo_analysis         │
│    fn_ComputeBadge        vw_file_abr_report            │
│                                                         │
│  procedures.sql                                         │
│    sp_RegisterStudent                                   │
│    sp_UpdateBadge                                       │
│    sp_DeleteFile                                        │
└───────────────────────────┬─────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│  MODELS                                                 │
│                                                         │
│  Student.php          fix state codes 01–59             │
│  FileMetadata.php     new                               │
│  CbrMetadata.php      new                               │
│  Tag.php              new                               │
│  RegistrationHistory.php  already exists                │
└───────────────────────────┬─────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│  REGISTRATION                                           │
│                                                         │
│  RegistrationController@store                           │
│    validate inputs                                      │
│    IC parse  →  email classify                          │
│    call sp_RegisterStudent                              │
│    write history + badge                                │
│                                                         │
│  RegistrationController (re-register)                   │
│    unlink old files                                     │
│    call sp_RegisterStudent (update path)                │
└───────────────────────────┬─────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│  UPLOAD PIPELINE                                        │
│                                                         │
│  UploadService (new)                                    │
│    finfo MIME check                                     │
│    size check                                           │
│    move to storage/uploads/{type}/                      │
│    insert file_metadata row                             │
│                                                         │
│  MetadataExtractor (new)                                │
│    photo  →  CBR fields                                 │
│    audio  →  duration, tier, bitrate                    │
│    video  →  resolution, tier, duration                 │
│    pdf    →  extracted_text                             │
│    insert cbr_metadata row                              │
│                                                         │
│  call sp_UpdateBadge after each insert                  │
└───────────────────────────┬─────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│  RETRIEVAL                                              │
│                                                         │
│  SearchController (new)                                 │
│                                                         │
│  ABR  →  vw_file_abr_report + active filters            │
│  TBR  →  MATCH extracted_text AGAINST (keyword)         │
│           + optional file_tags join                     │
│  CBR  →  vw_cbr_photo_analysis + cbr_metadata filters   │
└───────────────────────────┬─────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│  DASHBOARD + DETAIL                                     │
│                                                         │
│  DashboardController (new)                              │
│    total students                                       │
│    total files by type                                  │
│    badge distribution                                   │
│    recent registrations                                 │
│                                                         │
│  StudentController (new)                                │
│    student + files + cbr + history                      │
│    via vw_student_summary                               │
└───────────────────────────┬─────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│  DELETE + CLEANUP                                       │
│                                                         │
│  RegistrationController@deleteFile                      │
│    unlink physical file                                 │
│    call sp_DeleteFile                                   │
│    sp_DeleteFile calls sp_UpdateBadge                   │
│    log cleanup outcome                                  │
└─────────────────────────────────────────────────────────┘
```

## Build Order Rule

Each box depends on the one above it. Do not skip ahead.

```
schema  →  sql objects  →  models  →  registration
  →  upload  →  retrieval  →  dashboard  →  delete
```
