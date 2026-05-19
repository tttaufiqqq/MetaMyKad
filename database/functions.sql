DROP FUNCTION IF EXISTS fn_badge_by_file_count;
DROP FUNCTION IF EXISTS fn_email_category;
DROP FUNCTION IF EXISTS fn_age_from_dob;

DELIMITER $$

CREATE FUNCTION fn_badge_by_file_count(p_file_count INT)
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN
    RETURN CASE
        WHEN COALESCE(p_file_count, 0) >= 4 THEN 'Cemerlang'
        WHEN COALESCE(p_file_count, 0) = 3 THEN 'Dedikasi'
        WHEN COALESCE(p_file_count, 0) = 2 THEN 'Aktif'
        WHEN COALESCE(p_file_count, 0) = 1 THEN 'Pelajar'
        ELSE 'Pendaftar'
    END;
END $$

CREATE FUNCTION fn_email_category(p_email VARCHAR(100))
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN
    DECLARE v_domain VARCHAR(100);

    SET v_domain = LOWER(SUBSTRING_INDEX(COALESCE(p_email, ''), '@', -1));

    IF v_domain = '' THEN
        RETURN 'personal';
    END IF;

    IF v_domain REGEXP 'student|\\.edu\\.my$' THEN
        RETURN 'student';
    END IF;

    IF v_domain IN ('gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com') THEN
        RETURN 'personal';
    END IF;

    RETURN 'work';
END $$

CREATE FUNCTION fn_age_from_dob(p_dob DATE)
RETURNS INT
DETERMINISTIC
BEGIN
    IF p_dob IS NULL THEN
        RETURN 0;
    END IF;

    RETURN TIMESTAMPDIFF(YEAR, p_dob, CURDATE());
END $$

DELIMITER ;
