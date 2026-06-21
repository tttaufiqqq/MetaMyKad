-- ============================================================
-- MetaMyKad — Local Development SQL
-- Run in phpMyAdmin or MySQL Workbench as root / password.
-- Covers two tasks:
--   A. Apply migration 004 to your local metamykad database.
--   B. Set up mmdb2026 simulation database for cross-DB login
--      testing (creates the vstu view the app reads from).
-- ============================================================


-- ────────────────────────────────────────────────────────────
-- A. Migration 004 on local metamykad database
-- ────────────────────────────────────────────────────────────

USE metamykad;

ALTER TABLE students
    MODIFY COLUMN ic_number      VARCHAR(64)                            NULL,
    MODIFY COLUMN email          VARCHAR(100)                           NULL,
    MODIFY COLUMN email_category ENUM('personal','student','work')     NULL,
    MODIFY COLUMN date_of_birth  DATE                                   NULL,
    MODIFY COLUMN gender         ENUM('M','F')                          NULL,
    MODIFY COLUMN age            INT                                    NULL,
    MODIFY COLUMN state_of_birth VARCHAR(50)                            NULL;

DESCRIBE students;


-- ────────────────────────────────────────────────────────────
-- B. mmdb2026 simulation database
--    Mirrors the server's central student database so the
--    auto-stub login works identically during local testing.
--
--    SHA-256 password mappings used in test accounts:
--      '123'    → a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3
--      'qwerty' → 65e84be33532fb784c48129675f9eff3a682b27168c0ea744b2cf58ee02337c5
-- ────────────────────────────────────────────────────────────

CREATE DATABASE IF NOT EXISTS mmdb2026
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mmdb2026;

CREATE TABLE IF NOT EXISTS stu (
    id            INT           NOT NULL AUTO_INCREMENT PRIMARY KEY,
    matric_no     VARCHAR(20)   NOT NULL,
    full_name     VARCHAR(100)  NOT NULL,
    phone_no      VARCHAR(20)   DEFAULT NULL,
    group_no      VARCHAR(10)   DEFAULT NULL,
    life_motto    TEXT          DEFAULT NULL,
    password      VARCHAR(255)  DEFAULT NULL,
    photoStu      VARCHAR(500)  DEFAULT NULL,
    photoStu_date DATE          DEFAULT NULL,
    docStu        VARCHAR(500)  DEFAULT NULL,
    docStu_date   DATE          DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Re-seed (safe to re-run — TRUNCATE then re-insert)
TRUNCATE TABLE stu;

INSERT INTO stu (id, matric_no, full_name, phone_no, group_no, life_motto, password) VALUES
(1,  'B03234787',  'Hidayah',                          '01644578',    'GK02', NULL, NULL),
(4,  'B111',       'Fatimah',                          '121212',      'GK02', NULL, NULL),
-- password: qwerty
(5,  'B0231241',   'Wadi',                             '0178246',     'GR01', NULL,
     '65e84be33532fb784c48129675f9eff3a682b27168c0ea744b2cf58ee02337c5'),
(7,  'B0334',      'Rads',                             '0145434',     'GR01', NULL, NULL),
-- password: 123
(9,  'P02165',     'Norlizam',                         '065465',      'GR02', 'sabar_syukur_taqwa',
     'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
-- password: 123
(10, 'B032420099', 'MUHAMMAD TAUFIQ BIN MOHD ARIFIN',  '0138742846',  'GS02', NULL,
     'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
-- password: 123
(11, 'B032420121', 'NUR SAJIDAH BINTI ZANIAN',         '0147480610',  'GS04', NULL,
     'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
-- password: 123
(13, 'B032510300', 'NADIA BINTI SHAHRUL AZMEE',        '013-7918004', 'GS05', NULL,
     'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
-- password: 123
(14, 'B032410202', 'MUHAMMAD AFIQ HAZIM BIN ABD AZIZ', '01117964487', 'GR01',
     'HIDUP MESTI DITERUSKAN WALAU APA JUA SEKALIPON',
     'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
-- password: 123
(15, 'B032420087', 'MUHAMMAD HAIKAL BIN JOHARI',       '0175969369',  'GS05', NULL,
     'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
-- password: 123
(16, 'B032410815', 'HUDA NAJIHAH BINTI SUHAIMI',       '010-8423611', 'GR01', NULL,
     'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3');

-- vstu is what the app queries — mirror the server's structure
CREATE OR REPLACE VIEW vstu AS SELECT * FROM stu;

-- Verify both parts completed
SELECT 'mmdb2026.vstu rows:' AS info, COUNT(*) AS total FROM vstu;
USE metamykad;
SELECT 'students nullable check — should show YES for ic_number:' AS info;
SELECT COLUMN_NAME, IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'metamykad'
  AND TABLE_NAME   = 'students'
  AND COLUMN_NAME IN ('ic_number','email','email_category','date_of_birth','gender','age','state_of_birth')
ORDER BY ORDINAL_POSITION;
