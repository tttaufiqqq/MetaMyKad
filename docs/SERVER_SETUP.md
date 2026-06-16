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

---

# Windows Server (XAMPP) Deployment Guide

The server runs **XAMPP Apache**, not IIS. Use the Apache steps below.
Do not run the IIS setup script unless confirmed otherwise.

**Target URL:**
```
https://bitp3353.utem.edu.my/2026/all/GroupMDB/GS02/MetaMyKad/
```

**Project folder on server:**
```
E:\xampp\htdocs\2026\All\GroupMDB\GS02\MetaMyKad
```

**Git Bash path:**
```bash
/e/xampp/htdocs/2026/All/GroupMDB/GS02/MetaMyKad
```

> Windows folder names are not case-sensitive — `All` and `all` refer to the same folder.
> For `APP_URL`, use the browser URL exactly as shown above.

---

## Deployment Files

| File | Purpose |
|---|---|
| `.htaccess` | Apache root — forwards requests into `public/` |
| `public/.htaccess` | Apache — rewrites routes to `public/index.php` |
| `public/web.config` | IIS route rewriting (only if IIS is confirmed) |
| `.env.production.example` | Environment template |
| `scripts/check-deployment.php` | Deployment health check |
| `scripts/create-storage-folders.php` | Creates upload directories |
| `scripts/windows/setup-iis.ps1` | IIS setup (not needed for XAMPP) |
| `scripts/windows/import-database.ps1` | PowerShell DB import script |

---

## Step 1 — Required PHP Extensions

Ensure these are enabled in `E:\xampp\php\php.ini`:

```ini
extension=pdo
extension=pdo_mysql
extension=fileinfo
extension=mbstring
extension=gd
extension=exif
```

Recommended upload settings:

```ini
upload_max_filesize=100M
post_max_size=120M
max_execution_time=120
date.timezone=Asia/Singapore
```

To enable extensions automatically, run from **Administrator PowerShell**:

```powershell
$ini = "E:\xampp\php\php.ini"
$extensions = @("pdo_mysql", "fileinfo", "mbstring", "gd", "exif")
$content = Get-Content $ini

foreach ($ext in $extensions) {
    $content = $content -replace ("^\s*;\s*extension=" + [regex]::Escape($ext) + "\s*$"), ("extension=" + $ext)
    if (-not ($content -match ("^\s*extension=" + [regex]::Escape($ext) + "\s*$"))) {
        $content += "extension=$ext"
    }
}

Set-Content -Path $ini -Value $content
```

Restart Apache from the XAMPP Control Panel after changing `php.ini`.

Verify:

```bash
php -m | grep -E "pdo_mysql|fileinfo|mbstring|gd|exif"
```

---

## Step 2 — Install FFmpeg

From **Administrator PowerShell**:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force
[System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072
iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
choco install ffmpeg -y
```

If Chocolatey is already installed, only run:

```powershell
choco install ffmpeg -y
```

Close and reopen Git Bash, then verify:

```bash
ffmpeg -version
ffprobe -version
```

> FFmpeg is required for audio/video duration and bitrate extraction. The app can still upload files without it, but CBR metadata for audio/video will be incomplete.

---

## Step 3 — Pull Code and Install Dependencies

```bash
cd /e/xampp/htdocs/2026/All/GroupMDB/GS02/MetaMyKad
git pull
composer install --no-dev --optimize-autoloader
composer deploy:folders
```

---

## Step 4 — Configure .env

Windows Explorer cannot rename a file to `.env` (it shows "You must type a file name"). Use Git Bash:

```bash
cp .env.production.example .env
```

If `.env` already exists, do not replace it — check it:

```bash
cat .env
```

Required values:

```env
APP_NAME=MetaMyKad
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bitp3353.utem.edu.my/2026/all/GroupMDB/GS02/MetaMyKad
APP_TIMEZONE=Asia/Singapore

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=gs02
DB_CHARSET=utf8mb4
DB_USER=GS02
DB_PASS=1234
```

---

## Step 5 — Import the Database

**Git Bash (preferred):**

```bash
mysql -u root -p gs02 < database/schema.sql
mysql -u root -p gs02 < database/functions.sql
mysql -u root -p gs02 < database/views.sql
mysql -u root -p gs02 < database/procedures.sql
```

If `mysql` is not in PATH, use the XAMPP binary:

```bash
/e/xampp/mysql/bin/mysql.exe -u root -p gs02 < database/schema.sql
/e/xampp/mysql/bin/mysql.exe -u root -p gs02 < database/functions.sql
/e/xampp/mysql/bin/mysql.exe -u root -p gs02 < database/views.sql
/e/xampp/mysql/bin/mysql.exe -u root -p gs02 < database/procedures.sql
```

**PowerShell option:**

```bash
powershell.exe -ExecutionPolicy Bypass -File ./scripts/windows/import-database.ps1 -User root -Database gs02 -MySqlExe "E:\xampp\mysql\bin\mysql.exe"
```

**phpMyAdmin option:**

1. Open `http://localhost/phpmyadmin/`
2. Select the `gs02` database on the left
3. Click **Import** → **Choose File** → select the SQL file → **Go**
4. Repeat in order: `schema.sql` → `functions.sql` → `views.sql` → `procedures.sql`

> Import order matters: `schema.sql` must come first because functions, views, and procedures depend on the tables.

After importing, run:

```bash
composer deploy:check
```

---

## Step 6 — Apply Lecturer Server Fixes

Run the MySQL steps from the **Lecturer Server Setup** section above (Steps 1–4):

1. Fix `gs02` collation to `utf8mb4_0900_ai_ci`
2. Grant `GS02` read access to `mmdb2026.stu`
3. Apply the nullable-password migration
4. Reload routines in order: `functions.sql` → `views.sql` → `procedures.sql`

---

## Step 7 — Verify Apache Rewrite

Check that both `.htaccess` files exist:

```bash
ls -la .htaccess
ls -la public/.htaccess
```

If routes like `/dashboard` or `/register` return 404, Apache rewrite may be disabled.

In `E:\xampp\apache\conf\httpd.conf`, ensure:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

And in the `<Directory>` block for `htdocs`:

```apache
AllowOverride All
```

Restart Apache from the XAMPP Control Panel after any `httpd.conf` change.

---

## Step 8 — Run Deployment Check

```bash
composer deploy:check
```

This checks: PHP version, extensions, `vendor/autoload.php`, `.env`, `public/index.php`, `.htaccess` files, storage folders, storage write permission, and database connection.

Fix any `[FAIL]` items before testing the site.

---

## Step 9 — Test the App

```
https://bitp3353.utem.edu.my/2026/all/GroupMDB/GS02/MetaMyKad/
https://bitp3353.utem.edu.my/2026/all/GroupMDB/GS02/MetaMyKad/register
https://bitp3353.utem.edu.my/2026/all/GroupMDB/GS02/MetaMyKad/dashboard
https://bitp3353.utem.edu.my/2026/all/GroupMDB/GS02/MetaMyKad/students
```

> The XAMPP dashboard at `/dashboard/` is XAMPP itself — do not confuse it with the MetaMyKad dashboard at `.../MetaMyKad/dashboard`.

---

## Normal Update Flow

Whenever new code is pushed to the server:

```bash
cd /e/xampp/htdocs/2026/All/GroupMDB/GS02/MetaMyKad
git pull
composer install --no-dev --optimize-autoloader
composer deploy:folders
composer deploy:check
```

---

## Troubleshooting

### Page shows only XXXXXXXXX or a blank page

- Apache may be loading a stray `index.html`
- `.htaccess` is missing or old
- `APP_URL` is wrong

Fix:

```bash
git pull
composer install --no-dev --optimize-autoloader
composer deploy:folders
find . -maxdepth 2 -iname "index.*"
```

If `index.html` exists in the project root, rename it:

```bash
mv index.html index.html.bak
```

### 404 on /dashboard, /register, /students

- `mod_rewrite` disabled, or `AllowOverride All` not set, or `.htaccess` missing

Fix:

```bash
git pull && composer deploy:check
ls -la .htaccess public/.htaccess
```

Then check `httpd.conf` for `mod_rewrite` and `AllowOverride All`.

### Database connection error

Check `.env` values, then:

```bash
/e/xampp/mysql/bin/mysql.exe -u GS02 -p -e "SHOW DATABASES;"
composer deploy:check
```

### Upload permission error

```bash
composer deploy:folders
composer deploy:check
```

If still failing, check Windows permissions on:

```
E:\xampp\htdocs\2026\All\GroupMDB\GS02\MetaMyKad\storage
```

---

## Pre-Deployment Checklist

- [ ] `git pull` completed
- [ ] `.env` exists with correct `APP_URL`, `DB_NAME=gs02`, `DB_USER=GS02`
- [ ] `APP_ENV=production`, `APP_DEBUG=false`
- [ ] `composer install --no-dev --optimize-autoloader` completed
- [ ] `database/schema.sql` imported
- [ ] `database/functions.sql` imported
- [ ] `database/views.sql` imported
- [ ] `database/procedures.sql` imported
- [ ] `gs02` collation fixed to `utf8mb4_0900_ai_ci`
- [ ] `GS02` granted SELECT on `mmdb2026.stu`
- [ ] Nullable-password migration applied
- [ ] Routines reloaded after collation fix
- [ ] `.htaccess` exists
- [ ] `public/.htaccess` exists
- [ ] Apache `mod_rewrite` enabled
- [ ] Apache `AllowOverride All` set
- [ ] Required PHP extensions enabled
- [ ] `ffmpeg -version` and `ffprobe -version` work
- [ ] Storage folder is writable
- [ ] `composer deploy:check` passes
