-- ============================================================
-- MetaMyKad — Server Deployment SQL
-- Run this in phpMyAdmin logged in as GS02 (your server user).
-- Select the gs02 database first, then execute this file.
-- ============================================================

USE gs02;

-- Migration 004: allow NULL on columns not available from mmdb2026.vstu
-- Needed so auto-stub rows can be inserted on first login for central students.
-- Safe to re-run: MODIFY COLUMN on a nullable column is a no-op.

ALTER TABLE students
    MODIFY COLUMN ic_number      VARCHAR(64)                            NULL,
    MODIFY COLUMN email          VARCHAR(100)                           NULL,
    MODIFY COLUMN email_category ENUM('personal','student','work')     NULL,
    MODIFY COLUMN date_of_birth  DATE                                   NULL,
    MODIFY COLUMN gender         ENUM('M','F')                          NULL,
    MODIFY COLUMN age            INT                                    NULL,
    MODIFY COLUMN state_of_birth VARCHAR(50)                            NULL;

-- Verify: these columns should now show NULL in the Null column of DESCRIBE.
DESCRIBE students;
