-- Allow NULL for fields that cannot be populated from mmdb2026.vstu during auto-stub creation.
-- Stubs are inserted on first login when a central student has no MetaMyKad account yet.
-- The stub holds only matric_number, password, full_name, phone (all sourced from vstu).
-- Remaining fields are populated when the student completes their profile via /register.
-- Stub detection: ic_number IS NULL (set to hash only after IC is provided on completion).
ALTER TABLE students
    MODIFY COLUMN ic_number      VARCHAR(64)                            NULL,
    MODIFY COLUMN email          VARCHAR(100)                           NULL,
    MODIFY COLUMN email_category ENUM('personal','student','work')     NULL,
    MODIFY COLUMN date_of_birth  DATE                                   NULL,
    MODIFY COLUMN gender         ENUM('M','F')                          NULL,
    MODIFY COLUMN age            INT                                    NULL,
    MODIFY COLUMN state_of_birth VARCHAR(50)                            NULL;
