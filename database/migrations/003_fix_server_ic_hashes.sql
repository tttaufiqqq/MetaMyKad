-- Migration 003: fix truncated IC hashes on server
-- The ic_number column was VARCHAR(12) when SHA2 was first applied,
-- causing the 64-char hash to be silently truncated to 12 chars.
-- Run AFTER 002_widen_ic_number.sql

-- B032420099 — MUHAMMAD TAUFIQ BIN MOHD ARIFIN (IC: 040605011607)
UPDATE students
SET ic_number = SHA2('040605011607', 256)
WHERE matric_number = 'B032420099';

UPDATE registration_history
SET ic_number = SHA2('040605011607', 256)
WHERE ic_number = LEFT(SHA2('040605011607', 256), 12);

-- B032310253 — MUHAMMAD HALAL BIN ACHIM (IC: 020920100599)
UPDATE students
SET ic_number = SHA2('020920100599', 256)
WHERE matric_number = 'B032310253';

UPDATE registration_history
SET ic_number = SHA2('020920100599', 256)
WHERE ic_number = LEFT(SHA2('020920100599', 256), 12);
