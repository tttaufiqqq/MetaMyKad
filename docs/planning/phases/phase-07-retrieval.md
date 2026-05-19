# Project Plan - Phase 7: Retrieval (Search)
Created: 2026-05-19
Source: docs/planning/backend-flow.md

## Instructions
- Auto-commit code after each completed todo item
- Update this file every 5 completed items (checkpoint save)
- Do not commit this plan file — it is your AI's working reference

## Architecture

```
GET /search-attribute   -> SearchController@attributeSearch (ABR)
GET /search-text        -> SearchController@textSearch      (TBR)
GET /search-content     -> SearchController@contentSearch   (CBR)

ABR query path:
  vw_file_abr_report
    WHERE [active filters applied dynamically]
    filters: full_name LIKE, gender =, state_of_birth =,
             email_category =, badge =, file_type =, mime_type =

TBR query path:
  SELECT ... FROM file_metadata fm
  JOIN students s ON s.id = fm.student_id
  LEFT JOIN file_tags ft ON ft.file_id = fm.id
  LEFT JOIN tags t ON t.id = ft.tag_id
  WHERE MATCH(fm.extracted_text) AGAINST (:keyword IN BOOLEAN MODE)
    [AND t.name = :tag  (if tag filter active)]

CBR query path:
  SELECT ... FROM cbr_metadata c
  JOIN file_metadata fm ON fm.id = c.file_id
  JOIN students s ON s.id = fm.student_id
  WHERE [cbr filters applied dynamically]
  filters: photo_category =, dominant_expression =,
           audio_duration_tier =, audio_bitrate >= ,
           video_resolution_tier =, video_duration_sec BETWEEN

Result shape (all three search types):
  student name, IC (masked or full), matched file_type,
  key matched metadata field, link to /student-detail?id=...

Empty state rule:
  every search page MUST show active filters + "No matching records found"
  message — never a blank table
```

## Implementation Plan

### Phase 7a: Create SearchController

- [ ] Create src/Controllers/SearchController.php extending BaseController
- [ ] Register routes: change PageController stubs for /search-attribute, /search-text, /search-content to point to SearchController in config/routes.php
- [ ] Implement attributeSearch(): void — reads GET params, builds ABR query, passes results to view
- [ ] Implement textSearch(): void — reads GET params, builds TBR query, passes results to view
- [ ] Implement contentSearch(): void — reads GET params, builds CBR query, passes results to view

### Phase 7b: ABR Query (Attribute-Based Retrieval)

- [ ] In attributeSearch(), read optional GET params: name, gender, state_of_birth, email_category, badge, file_type, mime_type
- [ ] Start with base query: SELECT * FROM vw_file_abr_report WHERE 1=1
- [ ] For each active filter, append AND clause using PDO named placeholder — never concatenate user input directly into SQL
- [ ] If file_type or mime_type filter is active, the view already contains those columns — apply WHERE directly
- [ ] If no filters are active, return all rows (paginate if row count is large)
- [ ] Bind all filter values as PDO parameters
- [ ] Return results array to view; include active filters array for display

### Phase 7c: TBR Query (Text-Based Retrieval)

- [ ] In textSearch(), read GET params: keyword (required for FULLTEXT), exact_phrase (optional), tag (optional)
- [ ] If keyword is empty: render view with empty results and message "Enter a keyword to search"
- [ ] Build base query: SELECT s.id, s.full_name, s.ic_number, fm.file_type, fm.filename, fm.upload_date FROM file_metadata fm JOIN students s ON s.id=fm.student_id
- [ ] If tag filter active: add LEFT JOIN file_tags ft ON ft.file_id=fm.id LEFT JOIN tags t ON t.id=ft.tag_id and AND t.name = :tag
- [ ] If exact_phrase provided: use MATCH(fm.extracted_text) AGAINST (:phrase IN BOOLEAN MODE) with phrase wrapped in double quotes
- [ ] If keyword only: use MATCH(fm.extracted_text) AGAINST (:keyword IN BOOLEAN MODE)
- [ ] Fallback if FULLTEXT index is not active: use LIKE %keyword% on extracted_text (log a warning)
- [ ] Return results and active filters to view

### Phase 7d: CBR Query (Content-Based Retrieval)

- [ ] In contentSearch(), read GET params: photo_category, dominant_expression, audio_duration_tier, audio_bitrate_min, video_resolution_tier, video_duration_min, video_duration_max
- [ ] Build base query: SELECT s.id, s.full_name, s.ic_number, fm.file_type, c.photo_category, c.dominant_expression, c.audio_duration_tier, c.video_resolution_tier FROM cbr_metadata c JOIN file_metadata fm ON fm.id=c.file_id JOIN students s ON s.id=fm.student_id WHERE 1=1
- [ ] Append AND c.photo_category = :photo_category if filter active
- [ ] Append AND c.dominant_expression = :dominant_expression if filter active
- [ ] Append AND c.audio_duration_tier = :audio_duration_tier if filter active
- [ ] Append AND c.audio_bitrate >= :audio_bitrate_min if filter active
- [ ] Append AND c.video_resolution_tier = :video_resolution_tier if filter active
- [ ] Append AND c.video_duration_sec BETWEEN :video_duration_min AND :video_duration_max if both range values active
- [ ] Return results and active filters to view

### Phase 7e: Build ABR Search View (pages/search-attribute.php)

- [ ] Add filter form (GET method) with inputs: name text, gender select, state_of_birth select, email_category select, badge select, file_type select, mime_type text
- [ ] Populate select options from known ENUM values (gender M/F, email_category student/personal/work, badge Pendaftar/Pelajar/Aktif/Dedikasi/Cemerlang, file_type photo/audio/pdf/video)
- [ ] Show active filter summary above results table ("Showing results for: gender=M, badge=Aktif")
- [ ] Render results in a table: Full Name, IC, File Type, File Size, MIME Type, Upload Date, link to detail
- [ ] If results array is empty: show "No matching records found" instead of empty table

### Phase 7f: Build TBR Search View (pages/search-text.php)

- [ ] Add filter form (GET) with inputs: keyword text (required), exact_phrase text (optional), tag text (optional)
- [ ] Show active filter summary above results
- [ ] Render results in a table: Full Name, IC, File Type, Filename, Upload Date, link to detail
- [ ] If keyword was provided but no matches: show "No matching records found for keyword: {keyword}"
- [ ] If keyword was not provided: show prompt "Enter a keyword to search"

### Phase 7g: Build CBR Search View (pages/search-content.php)

- [ ] Add filter form (GET) with inputs: photo_category select, dominant_expression select, audio_duration_tier select, audio_bitrate_min number, video_resolution_tier select, video_duration_min number, video_duration_max number
- [ ] Populate select options for photo_category, dominant_expression, audio_duration_tier, video_resolution_tier from known values
- [ ] Show active filter summary above results
- [ ] Render results in a table: Full Name, IC, File Type, matched CBR field value, link to detail
- [ ] If no filters are active: show "Select at least one content filter to search"
- [ ] If filters active but no matches: show "No matching records found"

### Phase 7h: Integration Tests

- [ ] ABR: search gender=F, badge=Cemerlang — verify only female Cemerlang students appear
- [ ] ABR: search with no filters — verify all records are returned
- [ ] TBR: search keyword present in a PDF's extracted_text — verify that file appears in results
- [ ] TBR: search keyword not in any extracted_text — verify empty state message shown
- [ ] CBR: search audio_duration_tier=short — verify only short-tier audio files appear
- [ ] CBR: search video_resolution_tier=FHD — verify only FHD video files appear
- [ ] All three pages: verify active filter summary is displayed alongside results

## Progress Log

