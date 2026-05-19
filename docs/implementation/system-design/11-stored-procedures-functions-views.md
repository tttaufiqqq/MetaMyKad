# Stored Procedures, Functions, and Views

**Parent:** [Implementation specs](./README.md)
**Related:**
- [Database schema](./02-database-schema-and-sql-plan.md)
- [Registration flow](./03-registration-and-reregistration-flow.md)
- [Badge rules](./07-badge-history-and-audit-rules.md)
- [Retrieval contracts](./06-retrieval-and-search-contracts.md)

Status: Draft
Type: Implementation target

---

## Stored Procedures

### `sp_RegisterStudent`

Handles both new registration and re-registration in one atomic transaction. Logs to
`REGISTRATION_HISTORY`, clears all old file records, updates student data, and recomputes badge.
PHP calls this after file uploads succeed.

```sql
DELIMITER $$
CREATE PROCEDURE sp_RegisterStudent(
    IN p_ic VARCHAR(12),
    IN p_name VARCHAR(100),
    IN p_phone VARCHAR(15),
    IN p_email VARCHAR(100),
    IN p_email_cat VARCHAR(10),
    IN p_dob DATE,
    IN p_gender CHAR(1),
    IN p_state VARCHAR(50),
    IN p_age INT
)
BEGIN
    DECLARE v_student_id INT DEFAULT NULL;
    DECLARE v_file_count INT DEFAULT 0;
    DECLARE v_badge VARCHAR(20);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    SELECT id INTO v_student_id FROM students
    WHERE ic_number = p_ic FOR UPDATE;

    IF v_student_id IS NOT NULL THEN
        SELECT COUNT(*) INTO v_file_count FROM file_metadata
        WHERE student_id = v_student_id;
        SELECT badge INTO v_badge FROM students WHERE id = v_student_id;

        INSERT INTO registration_history
            (ic_number, registered_at, files_uploaded, badge_at_time, action)
        VALUES (p_ic, NOW(), v_file_count, v_badge, 'update');

        DELETE cbr FROM cbr_metadata cbr
        JOIN file_metadata fm ON cbr.file_id = fm.id
        WHERE fm.student_id = v_student_id;

        DELETE ft FROM file_tags ft
        JOIN file_metadata fm ON ft.file_id = fm.id
        WHERE fm.student_id = v_student_id;

        DELETE FROM file_metadata WHERE student_id = v_student_id;

        UPDATE students SET full_name=p_name, phone=p_phone,
            email=p_email, email_category=p_email_cat, updated_at=NOW()
        WHERE id = v_student_id;
    ELSE
        INSERT INTO students
            (ic_number, full_name, phone, email, email_category,
             date_of_birth, gender, state_of_birth, age, badge,
             created_at, updated_at)
        VALUES (p_ic, p_name, p_phone, p_email, p_email_cat,
                p_dob, p_gender, p_state, p_age, 'Pendaftar',
                NOW(), NOW());

        INSERT INTO registration_history
            (ic_number, registered_at, files_uploaded, badge_at_time, action)
        VALUES (p_ic, NOW(), 0, 'Pendaftar', 'new');
    END IF;

    COMMIT;
END$$
DELIMITER ;
```

---

### `sp_UpdateBadge`

Recomputes a student's badge based on current file count and updates the `students` table.
Called after every file upload or deletion.

```sql
DELIMITER $$
CREATE PROCEDURE sp_UpdateBadge(IN p_student_id INT)
BEGIN
    DECLARE v_count INT;
    DECLARE v_badge VARCHAR(20);

    SELECT COUNT(*) INTO v_count
    FROM file_metadata WHERE student_id = p_student_id;

    SET v_badge = CASE
        WHEN v_count = 0 THEN 'Pendaftar'
        WHEN v_count = 1 THEN 'Pelajar'
        WHEN v_count = 2 THEN 'Aktif'
        WHEN v_count = 3 THEN 'Dedikasi'
        ELSE 'Cemerlang'
    END;

    UPDATE students SET badge = v_badge WHERE id = p_student_id;
END$$
DELIMITER ;
```

---

### `sp_DeleteFile`

Deletes a single file's database records atomically — removes `cbr_metadata`, `file_tags`, and
`file_metadata` rows, then triggers badge recomputation. PHP handles the filesystem `unlink()`
before calling this procedure.

```sql
DELIMITER $$
CREATE PROCEDURE sp_DeleteFile(IN p_file_id INT)
BEGIN
    DECLARE v_student_id INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN ROLLBACK; END;

    START TRANSACTION;

    SELECT student_id INTO v_student_id
    FROM file_metadata WHERE id = p_file_id;

    DELETE FROM cbr_metadata WHERE file_id = p_file_id;
    DELETE FROM file_tags WHERE file_id = p_file_id;
    DELETE FROM file_metadata WHERE id = p_file_id;

    CALL sp_UpdateBadge(v_student_id);

    COMMIT;
END$$
DELIMITER ;
```

---

## Functions

### `fn_GetEmailCategory`

Accepts a full email address and returns its category string. Called during registration before
`INSERT` to populate the `email_category` column.

```sql
DELIMITER $$
CREATE FUNCTION fn_GetEmailCategory(p_email VARCHAR(100))
RETURNS VARCHAR(10)
DETERMINISTIC
BEGIN
    DECLARE v_domain VARCHAR(100);
    SET v_domain = LOWER(SUBSTRING_INDEX(p_email, '@', -1));

    IF v_domain IN ('gmail.com','yahoo.com','hotmail.com',
                    'outlook.com','icloud.com','live.com','me.com') THEN
        RETURN 'personal';
    ELSEIF v_domain LIKE '%.edu.my' OR v_domain LIKE '%.edu'
        OR v_domain LIKE '%.ac.my' OR v_domain LIKE '%.ac.uk' THEN
        RETURN 'student';
    ELSE
        RETURN 'work';
    END IF;
END$$
DELIMITER ;
```

---

### `fn_GetStateFromIC`

Accepts a 12-digit IC number string and returns the state of birth by reading the 7th and 8th
digits (PB code). Returns `'Unknown'` for unrecognised codes.

```sql
DELIMITER $$
CREATE FUNCTION fn_GetStateFromIC(p_ic VARCHAR(12))
RETURNS VARCHAR(50)
DETERMINISTIC
BEGIN
    DECLARE v_pb VARCHAR(2);
    SET v_pb = SUBSTRING(p_ic, 7, 2);

    RETURN CASE v_pb
        WHEN '01' THEN 'Johor'         WHEN '21' THEN 'Johor'
        WHEN '22' THEN 'Johor'         WHEN '23' THEN 'Johor'
        WHEN '24' THEN 'Johor'
        WHEN '02' THEN 'Kedah'         WHEN '25' THEN 'Kedah'
        WHEN '26' THEN 'Kedah'         WHEN '27' THEN 'Kedah'
        WHEN '03' THEN 'Kelantan'      WHEN '28' THEN 'Kelantan'
        WHEN '29' THEN 'Kelantan'
        WHEN '04' THEN 'Melaka'        WHEN '30' THEN 'Melaka'
        WHEN '05' THEN 'Negeri Sembilan' WHEN '31' THEN 'Negeri Sembilan'
        WHEN '59' THEN 'Negeri Sembilan'
        WHEN '06' THEN 'Pahang'        WHEN '32' THEN 'Pahang'
        WHEN '33' THEN 'Pahang'
        WHEN '07' THEN 'Penang'        WHEN '34' THEN 'Penang'
        WHEN '35' THEN 'Penang'
        WHEN '08' THEN 'Perak'         WHEN '36' THEN 'Perak'
        WHEN '37' THEN 'Perak'         WHEN '38' THEN 'Perak'
        WHEN '39' THEN 'Perak'
        WHEN '09' THEN 'Perlis'        WHEN '40' THEN 'Perlis'
        WHEN '10' THEN 'Selangor'      WHEN '41' THEN 'Selangor'
        WHEN '42' THEN 'Selangor'      WHEN '43' THEN 'Selangor'
        WHEN '44' THEN 'Selangor'
        WHEN '11' THEN 'Terengganu'    WHEN '45' THEN 'Terengganu'
        WHEN '46' THEN 'Terengganu'
        WHEN '12' THEN 'Sabah'         WHEN '47' THEN 'Sabah'
        WHEN '48' THEN 'Sabah'         WHEN '49' THEN 'Sabah'
        WHEN '13' THEN 'Sarawak'       WHEN '50' THEN 'Sarawak'
        WHEN '51' THEN 'Sarawak'       WHEN '52' THEN 'Sarawak'
        WHEN '53' THEN 'Sarawak'
        WHEN '14' THEN 'Kuala Lumpur'  WHEN '54' THEN 'Kuala Lumpur'
        WHEN '55' THEN 'Kuala Lumpur'  WHEN '56' THEN 'Kuala Lumpur'
        WHEN '57' THEN 'Kuala Lumpur'
        WHEN '15' THEN 'Labuan'        WHEN '58' THEN 'Labuan'
        WHEN '16' THEN 'Putrajaya'
        ELSE 'Unknown'
    END;
END$$
DELIMITER ;
```

---

### `fn_ComputeBadge`

Accepts a file count integer and returns the corresponding badge label string. Used by
`sp_UpdateBadge` and can be called directly in `SELECT` queries.

```sql
DELIMITER $$
CREATE FUNCTION fn_ComputeBadge(p_count INT)
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN
    RETURN CASE
        WHEN p_count = 0 THEN 'Pendaftar'
        WHEN p_count = 1 THEN 'Pelajar'
        WHEN p_count = 2 THEN 'Aktif'
        WHEN p_count = 3 THEN 'Dedikasi'
        ELSE 'Cemerlang'
    END;
END$$
DELIMITER ;
```

---

## Views

### `vw_student_summary`

Consolidated view of each student with file count, badge, and all IC-derived and email-derived
attributes. Used by the staff dashboard to list all registered students without complex JOINs in
application code.

```sql
CREATE VIEW vw_student_summary AS
SELECT
    s.id,
    s.ic_number,
    s.full_name,
    s.gender,
    s.state_of_birth,
    s.date_of_birth,
    s.age,
    s.email,
    s.email_category,
    s.badge,
    COUNT(fm.id) AS total_files,
    s.created_at,
    s.updated_at
FROM students s
LEFT JOIN file_metadata fm ON s.id = fm.student_id
GROUP BY s.id;
```

---

### `vw_cbr_photo_analysis`

Joins `students`, `file_metadata`, and `cbr_metadata` to expose all photo-level CBR attributes
alongside student demographics. Used directly by CBR search queries.

```sql
CREATE VIEW vw_cbr_photo_analysis AS
SELECT
    s.full_name,
    s.state_of_birth,
    s.gender,
    s.email_category,
    s.badge,
    c.photo_category,
    c.dominant_bg_color,
    c.bg_color_variance,
    c.dominant_expression,
    c.expression_confidence
FROM students s
JOIN file_metadata fm ON s.id = fm.student_id
JOIN cbr_metadata c ON fm.id = c.file_id
WHERE fm.file_type = 'photo';
```

---

### `vw_file_abr_report`

Presents all file-level ABR attributes joined with student identity and IC-derived fields. Used
by the ABR search interface to filter and sort by any combination of file and student attributes.

```sql
CREATE VIEW vw_file_abr_report AS
SELECT
    s.full_name,
    s.ic_number,
    s.gender,
    s.state_of_birth,
    s.email_category,
    fm.file_type,
    fm.filename,
    fm.file_size,
    fm.mime_type,
    fm.upload_date
FROM students s
JOIN file_metadata fm ON s.id = fm.student_id;
```
