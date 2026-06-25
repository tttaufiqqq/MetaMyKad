DROP VIEW IF EXISTS vw_badge_distribution;
DROP VIEW IF EXISTS vw_file_search_catalog;
DROP VIEW IF EXISTS vw_student_profile_summary;
DROP VIEW IF EXISTS vw_student_file_counts;

CREATE VIEW vw_student_file_counts AS
SELECT
    s.id AS student_id,
    s.ic_number,
    COUNT(f.id) AS total_files,
    SUM(CASE WHEN f.file_type = 'photo' THEN 1 ELSE 0 END) AS photo_count,
    SUM(CASE WHEN f.file_type = 'audio' THEN 1 ELSE 0 END) AS audio_count,
    SUM(CASE WHEN f.file_type = 'pdf' THEN 1 ELSE 0 END) AS pdf_count,
    SUM(CASE WHEN f.file_type = 'video' THEN 1 ELSE 0 END) AS video_count,
    MAX(f.upload_date) AS latest_upload_date,
    fn_badge_by_file_count(COUNT(f.id)) AS calculated_badge
FROM students s
LEFT JOIN file_metadata f
    ON f.student_id = s.id
GROUP BY s.id, s.ic_number;

CREATE VIEW vw_student_profile_summary AS
SELECT
    s.id AS student_id,
    s.matric_number,
    s.ic_number,
    fn_mask_ic(s.ic_number) AS ic_masked,
    s.full_name,
    s.phone,
    s.email,
    s.email_category,
    s.date_of_birth,
    fn_age_from_dob(s.date_of_birth) AS current_age,
    s.gender,
    s.state_of_birth,
    s.badge AS stored_badge,
    COALESCE(fc.total_files, 0) AS total_files,
    COALESCE(fc.photo_count, 0) AS photo_count,
    COALESCE(fc.audio_count, 0) AS audio_count,
    COALESCE(fc.pdf_count, 0) AS pdf_count,
    COALESCE(fc.video_count, 0) AS video_count,
    fc.latest_upload_date,
    COALESCE(fc.calculated_badge, fn_badge_by_file_count(0)) AS calculated_badge,
    COALESCE(rh.history_entries, 0) AS history_entries,
    rh.last_registered_at,
    COALESCE(fc.latest_upload_date, s.updated_at) AS latest_activity_at
FROM students s
LEFT JOIN vw_student_file_counts fc
    ON fc.student_id = s.id
LEFT JOIN (
    SELECT
        ic_number,
        COUNT(*) AS history_entries,
        MAX(registered_at) AS last_registered_at
    FROM registration_history
    GROUP BY ic_number
) rh
    ON rh.ic_number = s.ic_number;

CREATE VIEW vw_file_search_catalog AS
SELECT
    f.id AS file_id,
    s.id AS student_id,
    s.ic_number,
    s.full_name,
    s.email,
    s.state_of_birth,
    s.badge,
    f.file_type,
    f.filename,
    f.stored_filename,
    f.file_path,
    f.file_size,
    f.mime_type,
    f.upload_date,
    f.extracted_text,
    c.photo_category,
    c.bg_color_variance,
    c.dominant_bg_color,
    c.dominant_expression,
    c.expression_confidence,
    c.audio_duration_sec,
    c.audio_duration_tier,
    c.audio_bitrate,
    c.video_resolution,
    c.video_resolution_tier,
    c.video_duration_sec,
    GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name SEPARATOR ', ') AS tag_list
FROM file_metadata f
JOIN students s
    ON s.id = f.student_id
LEFT JOIN cbr_metadata c
    ON c.file_id = f.id
LEFT JOIN file_tags ft
    ON ft.file_id = f.id
LEFT JOIN tags t
    ON t.id = ft.tag_id
GROUP BY
    f.id,
    s.id,
    s.ic_number,
    s.full_name,
    s.email,
    s.state_of_birth,
    s.badge,
    f.file_type,
    f.filename,
    f.stored_filename,
    f.file_path,
    f.file_size,
    f.mime_type,
    f.upload_date,
    f.extracted_text,
    c.photo_category,
    c.bg_color_variance,
    c.dominant_bg_color,
    c.dominant_expression,
    c.expression_confidence,
    c.audio_duration_sec,
    c.audio_duration_tier,
    c.audio_bitrate,
    c.video_resolution,
    c.video_resolution_tier,
    c.video_duration_sec;

CREATE VIEW vw_badge_distribution AS
SELECT
    badge,
    COUNT(*) AS total_students
FROM students
GROUP BY badge;
