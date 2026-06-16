-- Local seed for mmdb2026 (lecturer's centralized DB)
-- Seeded from screenshot — 11 visible rows out of 101 total
-- Used for Option C cross-DB auth testing only

CREATE DATABASE IF NOT EXISTS mmdb2026
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mmdb2026;

CREATE TABLE IF NOT EXISTS stu (
    id           INT            NOT NULL AUTO_INCREMENT PRIMARY KEY,
    matric_no    VARCHAR(20)    NOT NULL,
    full_name    VARCHAR(100)   NOT NULL,
    phone_no     VARCHAR(20)    DEFAULT NULL,
    group_no     VARCHAR(10)    DEFAULT NULL,
    life_motto   TEXT           DEFAULT NULL,
    password     VARCHAR(255)   DEFAULT NULL,
    photoStu     VARCHAR(500)   DEFAULT NULL,
    photoStu_date DATE          DEFAULT NULL,
    docStu       VARCHAR(500)   DEFAULT NULL,
    docStu_date  DATE           DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Clear and re-seed (idempotent)
TRUNCATE TABLE stu;

INSERT INTO stu (id, matric_no, full_name, phone_no, group_no, life_motto, password, photoStu, photoStu_date, docStu, docStu_date) VALUES
(1,  'B03234787',  'Hidayah',                          '01644578',    'GK02', NULL,
     NULL,
     'uploads/1778232989_Gemini_Generated_Image_fwt7evfw.jpg', NULL, NULL, NULL),

(4,  'B111',       'Fatimah',                          '121212',      'GK02', NULL,
     NULL,
     'uploads/1778249245_WhatsApp_Image_2026-04-28.jpg', NULL,
     'uploads/1778249245_Lens_and_Algorithm.pdf', NULL),

(5,  'B0231241',   'Wadi',                             '0178246',     'GR01', NULL,
     'qwerty',
     'uploads/B0231241/B0231241_20260518_184121_Gemini_G.jpg', NULL,
     'uploads/1778746010_Surah_Al_Fil.pdf', NULL),

(7,  'B0334',      'Rads',                             '0145434',     'GR01', NULL,
     NULL,
     'uploads/1778751144_hidayah_floweryTul.png', NULL, NULL, NULL),

(9,  'P02165',     'Norlizam',                         '065465',      'GR02', 'sabar_syukur_taqwa',
     '123',
     'uploads/1779151585_Gemini_Generated.jpg', NULL,
     'uploads/1779024156_PRESENTATION_SCHEDULE.pdf', NULL),

(10, 'B032420099', 'MUHAMMAD TAUFIQ BIN MOHD ARIFIN',  '0138742846',  'GS02', NULL,
     '123',
     'uploads/1779151173_gambaq.jpg', '2025-03-07',
     'uploads/1779151173_syair-taufiq.pdf', '2026-04-04'),

(11, 'B032420121', 'NUR SAJIDAH BINTI ZANIAN',         '0147480610',  'GS04', NULL,
     '123',
     'uploads/1779151265_gambar.jpg', '2026-05-19',
     'uploads/1779151265_lagu.pdf', '2026-04-05'),

(13, 'B032510300', 'NADIA BINTI SHAHRUL AZMEE',        '013-7918004', 'GS05', NULL,
     '123',
     'uploads/1779151315_2.jpg', '2026-04-10',
     'uploads/1779151315_Lab_Activity_Sound_of_Ma.pdf', '2026-03-24'),

(14, 'B032410202', 'MUHAMMAD AFIQ HAZIM BIN ABD AZIZ', '01117964487', 'GR01',
     'HIDUP MESTI DITERUSKAN WALAU APA JUA SEKALIPON',
     '123',
     'uploads/1779151585_WhatsApp_Image_2026-05-19.jpg', '2026-05-19',
     'uploads/1779151585_Poem_Afiq.pdf', '2026-03-24'),

(15, 'B032420087', 'MUHAMMAD HAIKAL BIN JOHARI',       '0175969369',  'GS05', NULL,
     '123',
     'uploads/1779151786_plpicture.jpg', '2025-07-01',
     'uploads/1779151786_LAB_2_MDB.docx', '2026-03-29'),

(16, 'B032410815', 'HUDA NAJIHAH BINTI SUHAIMI',       '010-8423611', 'GR01', NULL,
     '123',
     'uploads/1779151815_PHOTO_B032410815_HUDA_NAJIHAH.jpg', '2026-04-13',
     'uploads/1779151815_POEM_B032410815_HUDA_NAJIHAH.pdf', '2026-03-26');
