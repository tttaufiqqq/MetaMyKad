DROP PROCEDURE IF EXISTS sp_refresh_student_badge;
DROP PROCEDURE IF EXISTS sp_write_registration_history;
DROP PROCEDURE IF EXISTS sp_get_student_history;
DROP PROCEDURE IF EXISTS sp_delete_file_metadata_db;
DROP PROCEDURE IF EXISTS sp_search_attribute_students;
DROP PROCEDURE IF EXISTS sp_search_text_files;
DROP PROCEDURE IF EXISTS sp_search_content_files;

DELIMITER $$

CREATE PROCEDURE sp_refresh_student_badge(IN p_student_id INT)
BEGIN
    DECLARE v_total_files INT DEFAULT 0;

    SELECT COUNT(*)
    INTO v_total_files
    FROM file_metadata
    WHERE student_id = p_student_id;

    UPDATE students
    SET
        badge = fn_badge_by_file_count(v_total_files),
        updated_at = NOW()
    WHERE id = p_student_id;

    SELECT *
    FROM vw_student_profile_summary
    WHERE student_id = p_student_id;
END $$

CREATE PROCEDURE sp_write_registration_history(IN p_ic_number VARCHAR(12), IN p_action VARCHAR(10))
BEGIN
    DECLARE v_student_id INT DEFAULT NULL;
    DECLARE v_files_uploaded INT DEFAULT 0;
    DECLARE v_badge VARCHAR(20) DEFAULT 'Pendaftar';

    SELECT id, badge
    INTO v_student_id, v_badge
    FROM students
    WHERE ic_number = p_ic_number
    LIMIT 1;

    IF v_student_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Student not found for registration history.';
    END IF;

    SELECT COUNT(*)
    INTO v_files_uploaded
    FROM file_metadata
    WHERE student_id = v_student_id;

    INSERT INTO registration_history (ic_number, files_uploaded, badge_at_time, action)
    VALUES (p_ic_number, v_files_uploaded, v_badge, p_action);

    SELECT *
    FROM registration_history
    WHERE id = LAST_INSERT_ID();
END $$

CREATE PROCEDURE sp_get_student_history(IN p_ic_number VARCHAR(12))
BEGIN
    SELECT *
    FROM registration_history
    WHERE ic_number = p_ic_number
    ORDER BY registered_at DESC, id DESC;
END $$

CREATE PROCEDURE sp_delete_file_metadata_db(IN p_file_id INT)
BEGIN
    DECLARE v_student_id INT DEFAULT NULL;
    DECLARE v_file_path VARCHAR(500) DEFAULT NULL;
    DECLARE v_remaining_files INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT student_id, file_path
    INTO v_student_id, v_file_path
    FROM file_metadata
    WHERE id = p_file_id
    LIMIT 1;

    IF v_student_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'File metadata not found.';
    END IF;

    START TRANSACTION;

    DELETE FROM file_tags
    WHERE file_id = p_file_id;

    DELETE FROM cbr_metadata
    WHERE file_id = p_file_id;

    DELETE FROM file_metadata
    WHERE id = p_file_id;

    SELECT COUNT(*)
    INTO v_remaining_files
    FROM file_metadata
    WHERE student_id = v_student_id;

    UPDATE students
    SET
        badge = fn_badge_by_file_count(v_remaining_files),
        updated_at = NOW()
    WHERE id = v_student_id;

    COMMIT;

    SELECT
        v_student_id AS student_id,
        v_file_path AS deleted_file_path,
        v_remaining_files AS remaining_files,
        fn_badge_by_file_count(v_remaining_files) AS recalculated_badge;
END $$

CREATE PROCEDURE sp_search_attribute_students(
    IN p_gender CHAR(1),
    IN p_state VARCHAR(50),
    IN p_email_category VARCHAR(20),
    IN p_badge VARCHAR(20),
    IN p_file_type VARCHAR(20)
)
BEGIN
    SELECT DISTINCT
        v.student_id,
        v.ic_number,
        v.full_name,
        v.email,
        v.gender,
        v.state_of_birth,
        v.stored_badge,
        v.total_files,
        f.file_type
    FROM vw_student_profile_summary v
    LEFT JOIN file_metadata f
        ON f.student_id = v.student_id
    WHERE (p_gender IS NULL OR p_gender = '' OR v.gender = p_gender)
      AND (p_state IS NULL OR p_state = '' OR v.state_of_birth = p_state)
      AND (p_email_category IS NULL OR p_email_category = '' OR v.email_category = p_email_category)
      AND (p_badge IS NULL OR p_badge = '' OR v.stored_badge = p_badge)
      AND (p_file_type IS NULL OR p_file_type = '' OR f.file_type = p_file_type)
    ORDER BY v.full_name ASC;
END $$

CREATE PROCEDURE sp_search_text_files(IN p_keyword VARCHAR(255), IN p_tag VARCHAR(50))
BEGIN
    SELECT
        v.file_id,
        v.student_id,
        v.full_name,
        v.file_type,
        v.filename,
        v.mime_type,
        v.upload_date,
        v.tag_list
    FROM vw_file_search_catalog v
    WHERE (
            p_keyword IS NULL
            OR p_keyword = ''
            OR MATCH(v.extracted_text) AGAINST (p_keyword IN NATURAL LANGUAGE MODE)
          )
      AND (
            p_tag IS NULL
            OR p_tag = ''
            OR COALESCE(v.tag_list, '') LIKE CONCAT('%', p_tag, '%')
          )
    ORDER BY v.upload_date DESC, v.file_id DESC;
END $$

CREATE PROCEDURE sp_search_content_files(
    IN p_photo_category VARCHAR(20),
    IN p_expression VARCHAR(20),
    IN p_audio_tier VARCHAR(20),
    IN p_video_tier VARCHAR(20)
)
BEGIN
    SELECT
        v.file_id,
        v.student_id,
        v.full_name,
        v.file_type,
        v.photo_category,
        v.dominant_expression,
        v.audio_duration_tier,
        v.video_resolution_tier,
        v.upload_date
    FROM vw_file_search_catalog v
    WHERE (p_photo_category IS NULL OR p_photo_category = '' OR v.photo_category = p_photo_category)
      AND (p_expression IS NULL OR p_expression = '' OR v.dominant_expression = p_expression)
      AND (p_audio_tier IS NULL OR p_audio_tier = '' OR v.audio_duration_tier = p_audio_tier)
      AND (p_video_tier IS NULL OR p_video_tier = '' OR v.video_resolution_tier = p_video_tier)
    ORDER BY v.upload_date DESC, v.file_id DESC;
END $$

DELIMITER ;
