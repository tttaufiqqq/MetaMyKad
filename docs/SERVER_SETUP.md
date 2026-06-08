# Lecturer Server Setup Checklist

Steps to run **once** on the lecturer's MySQL server before deploying MetaMyKad.
Run everything as `root` (or any user with SUPER privilege).

---

## Prerequisites

- `mmdb2026` database already exists with the `stu` table (lecturer's central DB)
- `gs02` database already exists and the schema + seed data has been loaded
- MySQL user `GS02` with password `1234` already exists with `ALL ON gs02.*`

---

## Step 1 — Fix `gs02` database collation

```sql
ALTER DATABASE gs02 CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
```

**Why:** MySQL uses `collation_database` as the implicit collation for stored
procedure parameters that have no explicit `CHARSET/COLLATE`. If `gs02` was
created with `utf8mb4_unicode_ci` (common via phpMyAdmin), every VARCHAR
parameter in every procedure silently gets `utf8mb4_unicode_ci`, clashing
with the `utf8mb4_0900_ai_ci` table columns and causing error 1267 on all
search pages (ABR / TBR / CBR).

---

## Step 2 — Grant cross-database read access to GS02

```sql
GRANT SELECT ON mmdb2026.stu TO 'GS02'@'localhost';
FLUSH PRIVILEGES;
```

**Why:** The login flow and the Students page query `mmdb2026.stu` directly
via a cross-database join from the `gs02` connection. Without this grant,
every login attempt and the `/students` page will throw an access-denied
error.

---

## Step 3 — Run the password-nullable migration

```sql
-- Or load the file:
-- mysql -u root -p gs02 < database/migrations/001_nullable_password.sql
ALTER TABLE students MODIFY COLUMN password VARCHAR(255) DEFAULT NULL;
```

**Why:** Authentication is now delegated to `mmdb2026.stu`. MetaMyKad no
longer stores passwords, so the column must allow NULL. Without this,
`sp_register_student` will fail when inserting a new student.

---

## Step 4 — Reload routines in the correct order

Run from the project root on the server:

```bash
mysql -u root -p --default-character-set=utf8mb4 gs02 < database/functions.sql
mysql -u root -p --default-character-set=utf8mb4 gs02 < database/views.sql
mysql -u root -p --default-character-set=utf8mb4 gs02 < database/procedures.sql
```

**Why:** Existing routines compiled under the old database collation retain
the old collation in their metadata even after Step 1. They must be dropped
and recreated **after** fixing the database collation.

Order matters:
- `functions.sql` first — views call functions, so functions must exist with
  the correct return collation before views compile their column types.
- `views.sql` second — procedures call views.
- `procedures.sql` last.

---

## Step 5 — Verify

```sql
-- Database collation should be utf8mb4_0900_ai_ci
SHOW CREATE DATABASE gs02;

-- All routines should show utf8mb4_0900_ai_ci
SELECT ROUTINE_NAME, COLLATION_CONNECTION
FROM INFORMATION_SCHEMA.ROUTINES
WHERE ROUTINE_SCHEMA = 'gs02'
ORDER BY ROUTINE_NAME;

-- GS02 user should be able to read mmdb2026.stu
-- (run as GS02 user)
SELECT COUNT(*) FROM mmdb2026.stu;

-- All three search procedures should execute without error
CALL gs02.sp_search_attribute_students(NULL, NULL, NULL, NULL, NULL);
CALL gs02.sp_search_text_files(NULL, NULL);
CALL gs02.sp_search_content_files(NULL, NULL, NULL, NULL);
```

---

## Quick Reference — What uses mmdb2026.stu

| Feature | Query |
|---------|-------|
| Student login | `SELECT ... FROM mmdb2026.stu WHERE matric_no = ?` |
| Register page prefill | `SELECT ... FROM mmdb2026.stu WHERE matric_no = ?` |
| Register POST validation | `SELECT ... FROM mmdb2026.stu WHERE matric_no = ?` |
| Students page listing | `SELECT ... FROM mmdb2026.stu LEFT JOIN gs02.students ...` |
