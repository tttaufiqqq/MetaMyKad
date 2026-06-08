DROP FUNCTION IF EXISTS fn_badge_by_file_count;
DROP FUNCTION IF EXISTS fn_email_category;
DROP FUNCTION IF EXISTS fn_age_from_dob;
DROP FUNCTION IF EXISTS fn_state_from_ic;

DELIMITER $$

CREATE FUNCTION fn_badge_by_file_count(p_file_count INT)
RETURNS VARCHAR(20) CHARSET utf8mb4 COLLATE utf8mb4_0900_ai_ci
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
RETURNS VARCHAR(20) CHARSET utf8mb4 COLLATE utf8mb4_0900_ai_ci
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

CREATE FUNCTION fn_state_from_ic(p_ic VARCHAR(12))
RETURNS VARCHAR(50) CHARSET utf8mb4 COLLATE utf8mb4_0900_ai_ci
DETERMINISTIC
BEGIN
    DECLARE v_code VARCHAR(2);
    SET v_code = SUBSTRING(p_ic, 7, 2);
    RETURN CASE v_code
        WHEN '01' THEN 'Johor'                          WHEN '21' THEN 'Johor'
        WHEN '22' THEN 'Johor'                          WHEN '23' THEN 'Johor'
        WHEN '24' THEN 'Johor'
        WHEN '02' THEN 'Kedah'                          WHEN '25' THEN 'Kedah'
        WHEN '26' THEN 'Kedah'                          WHEN '27' THEN 'Kedah'
        WHEN '03' THEN 'Kelantan'                       WHEN '28' THEN 'Kelantan'
        WHEN '29' THEN 'Kelantan'
        WHEN '04' THEN 'Melaka'                         WHEN '30' THEN 'Melaka'
        WHEN '05' THEN 'Negeri Sembilan'                WHEN '31' THEN 'Negeri Sembilan'
        WHEN '59' THEN 'Negeri Sembilan'
        WHEN '06' THEN 'Pahang'                         WHEN '32' THEN 'Pahang'
        WHEN '33' THEN 'Pahang'
        WHEN '07' THEN 'Pulau Pinang'                   WHEN '34' THEN 'Pulau Pinang'
        WHEN '35' THEN 'Pulau Pinang'
        WHEN '08' THEN 'Perak'                          WHEN '36' THEN 'Perak'
        WHEN '37' THEN 'Perak'                          WHEN '38' THEN 'Perak'
        WHEN '39' THEN 'Perak'
        WHEN '09' THEN 'Perlis'                         WHEN '40' THEN 'Perlis'
        WHEN '10' THEN 'Selangor'                       WHEN '41' THEN 'Selangor'
        WHEN '42' THEN 'Selangor'                       WHEN '43' THEN 'Selangor'
        WHEN '44' THEN 'Selangor'
        WHEN '11' THEN 'Terengganu'                     WHEN '45' THEN 'Terengganu'
        WHEN '46' THEN 'Terengganu'
        WHEN '12' THEN 'Sabah'                          WHEN '47' THEN 'Sabah'
        WHEN '48' THEN 'Sabah'                          WHEN '49' THEN 'Sabah'
        WHEN '13' THEN 'Sarawak'                        WHEN '50' THEN 'Sarawak'
        WHEN '51' THEN 'Sarawak'                        WHEN '52' THEN 'Sarawak'
        WHEN '53' THEN 'Sarawak'
        WHEN '14' THEN 'Wilayah Persekutuan Kuala Lumpur'
        WHEN '54' THEN 'Wilayah Persekutuan Kuala Lumpur'
        WHEN '55' THEN 'Wilayah Persekutuan Kuala Lumpur'
        WHEN '56' THEN 'Wilayah Persekutuan Kuala Lumpur'
        WHEN '57' THEN 'Wilayah Persekutuan Kuala Lumpur'
        WHEN '15' THEN 'Wilayah Persekutuan Labuan'     WHEN '58' THEN 'Wilayah Persekutuan Labuan'
        WHEN '16' THEN 'Wilayah Persekutuan Putrajaya'
        ELSE 'Unknown'
    END;
END $$

DELIMITER ;
