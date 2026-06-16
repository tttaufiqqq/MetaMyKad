DROP PROCEDURE IF EXISTS sp_refresh_student_badge;
DROP PROCEDURE IF EXISTS sp_write_registration_history;
DROP PROCEDURE IF EXISTS sp_get_student_history;
DROP PROCEDURE IF EXISTS sp_delete_file_metadata_db;
DROP PROCEDURE IF EXISTS sp_search_attribute_students;
DROP PROCEDURE IF EXISTS sp_search_text_files;
DROP PROCEDURE IF EXISTS sp_search_content_files;
DROP PROCEDURE IF EXISTS sp_register_student;
DROP PROCEDURE IF EXISTS sp_update_badge;
DROP PROCEDURE IF EXISTS sp_delete_file;

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
    DECLARE v_full_name VARCHAR(100) DEFAULT '';
    DECLARE v_files_uploaded INT DEFAULT 0;
    DECLARE v_badge VARCHAR(20) DEFAULT 'Pendaftar';

    SELECT id, full_name, badge
    INTO v_student_id, v_full_name, v_badge
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

    INSERT INTO registration_history (ic_number, full_name, files_uploaded, badge_at_time, action)
    VALUES (p_ic_number, v_full_name, v_files_uploaded, v_badge, p_action);

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
    -- MATCH...AGAINST requires the base table, not a view (MySQL error 1214).
    -- Join file_metadata directly so the FULLTEXT index on extracted_text is usable.
    SELECT
        f.id         AS file_id,
        s.id         AS student_id,
        s.full_name,
        f.file_type,
        f.filename,
        f.mime_type,
        f.upload_date,
        GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name SEPARATOR ', ') AS tag_list
    FROM file_metadata f
    JOIN students s ON s.id = f.student_id
    LEFT JOIN file_tags ft ON ft.file_id = f.id
    LEFT JOIN tags t ON t.id = ft.tag_id
    WHERE (
            p_keyword IS NULL
            OR p_keyword = ''
            OR MATCH(f.extracted_text) AGAINST (p_keyword IN NATURAL LANGUAGE MODE)
          )
      AND (
            p_tag IS NULL
            OR p_tag = ''
            OR EXISTS (
                SELECT 1
                FROM file_tags ft2
                JOIN tags t2 ON t2.id = ft2.tag_id
                WHERE ft2.file_id = f.id
                  AND t2.tag_name LIKE CONCAT('%', p_tag, '%')
            )
          )
    GROUP BY f.id, s.id, s.full_name, f.file_type, f.filename, f.mime_type, f.upload_date
    ORDER BY f.upload_date DESC, f.id DESC;
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

-- -------------------------------------------------------------------------
-- sp_register_student
-- Atomic new registration and re-registration in a single call.
-- For new:    inserts students row + history row (action='new').
-- For update: snapshots history, deletes all old file records (FK cascade
--             removes cbr_metadata and file_tags), updates contact fields.
-- PHP calls this BEFORE inserting file_metadata rows.
-- matric_number and password are ignored on re-registration (pass NULL).
-- -------------------------------------------------------------------------
CREATE PROCEDURE sp_register_student(
    IN p_ic          VARCHAR(12),
    IN p_matric      VARCHAR(20),
    IN p_password    VARCHAR(255),
    IN p_name        VARCHAR(100),
    IN p_phone       VARCHAR(15),
    IN p_email       VARCHAR(100),
    IN p_email_cat   VARCHAR(20),
    IN p_dob         DATE,
    IN p_gender      CHAR(1),
    IN p_state       VARCHAR(50),
    IN p_age         INT
)
BEGIN
    DECLARE v_student_id INT DEFAULT NULL;
    DECLARE v_file_count INT DEFAULT 0;
    DECLARE v_badge      VARCHAR(20) DEFAULT 'Pendaftar';

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    SELECT id INTO v_student_id
    FROM students
    WHERE ic_number = p_ic
    FOR UPDATE;

    IF v_student_id IS NOT NULL THEN
        -- Re-registration: snapshot current state then wipe old files
        SELECT COUNT(*) INTO v_file_count FROM file_metadata WHERE student_id = v_student_id;
        SELECT badge    INTO v_badge      FROM students         WHERE id = v_student_id;

        INSERT INTO registration_history (ic_number, full_name, files_uploaded, badge_at_time, action)
        SELECT p_ic, full_name, v_file_count, v_badge, 'update'
        FROM students WHERE id = v_student_id;

        -- PHP selectively deletes old file_metadata rows (only for replaced types)
        -- so we do NOT delete all files here any more.

        UPDATE students
        SET full_name      = p_name,
            phone          = p_phone,
            email          = p_email,
            email_category = p_email_cat,
            updated_at     = NOW()
        WHERE id = v_student_id;
    ELSE
        -- New registration
        INSERT INTO students
            (ic_number, matric_number, password, full_name, phone, email,
             email_category, date_of_birth, gender, state_of_birth, age,
             badge, created_at, updated_at)
        VALUES
            (p_ic, p_matric, p_password, p_name, p_phone, p_email,
             p_email_cat, p_dob, p_gender, p_state, p_age,
             'Pendaftar', NOW(), NOW());

        SET v_student_id = LAST_INSERT_ID();

        INSERT INTO registration_history (ic_number, full_name, files_uploaded, badge_at_time, action)
        VALUES (p_ic, p_name, 0, 'Pendaftar', 'new');
    END IF;

    COMMIT;

    SELECT v_student_id AS student_id;
END $$

-- -------------------------------------------------------------------------
-- sp_update_badge
-- Recomputes and stores the badge for one student from current file count.
-- Called after every file upload or deletion.
-- -------------------------------------------------------------------------
CREATE PROCEDURE sp_update_badge(IN p_student_id INT)
BEGIN
    DECLARE v_count INT DEFAULT 0;

    SELECT COUNT(*) INTO v_count
    FROM file_metadata
    WHERE student_id = p_student_id;

    UPDATE students
    SET badge      = fn_badge_by_file_count(v_count),
        updated_at = NOW()
    WHERE id = p_student_id;
END $$

-- -------------------------------------------------------------------------
-- sp_delete_file
-- Removes one file's DB records atomically, then recomputes the badge.
-- FK CASCADE on file_metadata handles cbr_metadata and file_tags.
-- PHP calls unlink() AFTER this procedure succeeds.
-- Returns student_id so PHP can redirect to the correct detail page.
-- -------------------------------------------------------------------------
CREATE PROCEDURE sp_delete_file(IN p_file_id INT)
BEGIN
    DECLARE v_student_id INT DEFAULT NULL;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT student_id INTO v_student_id
    FROM file_metadata
    WHERE id = p_file_id;

    IF v_student_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'File not found.';
    END IF;

    START TRANSACTION;

    DELETE FROM file_metadata WHERE id = p_file_id;

    CALL sp_update_badge(v_student_id);

    COMMIT;

    SELECT v_student_id AS student_id;
END $$

DELIMITER ;
