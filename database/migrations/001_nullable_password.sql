-- Migration 001: make students.password nullable
-- Reason: authentication is now delegated to mmdb2026.stu (Option C cross-DB auth).
-- MetaMyKad no longer stores or verifies passwords.

ALTER TABLE students
    MODIFY COLUMN password VARCHAR(255) DEFAULT NULL;
