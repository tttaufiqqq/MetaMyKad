# MetaMyKad - Local Development Setup

**Parent:** [Planning docs](./README.md)  
**Related:**
- [Implementation flow](./implementation-flow.md)
- [Pure PHP architecture](../implementation/system-design/01-pure-php-architecture.md)

---

## Prerequisites

- PHP 8.2 or newer
- MySQL 8.0 or newer
- Composer
- Git
- `ffmpeg` and `ffprobe` for video inspection
- Tesseract OCR or Poppler tools if OCR fallback is used for PDFs

## Recommended Local Stack

Any of these are acceptable:

- XAMPP
- Laragon
- Apache + PHP + MySQL installed separately
- PHP built-in server for early development

## Suggested Project Layout

The implementation docs assume these top-level folders will exist:

```text
config/
public/
src/
storage/uploads/
storage/tmp/
database/
vendor/
```

## Database Setup

1. Create a database named `metamykad`.
2. Ensure the charset is `utf8mb4`.
3. Use InnoDB for all tables.
4. Apply the SQL files in this order:
   - `database/schema.sql`
   - `database/functions.sql`
   - `database/views.sql`
   - `database/procedures.sql`

Example:

```sql
CREATE DATABASE metamykad
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

## Application Config

Use a PHP config file rather than a framework config system.

Suggested file:

```text
config/config.php
```

Suggested values:

```php
<?php
return [
    'db_host' => '127.0.0.1',
    'db_name' => 'metamykad',
    'db_user' => 'root',
    'db_pass' => '',
    'base_url' => 'http://localhost:8000',
    'upload_root' => __DIR__ . '/../storage/uploads',
    'max_upload_mb' => 50,
];
```

## Composer Packages Allowed

Pure PHP does not mean "no packages". It means no full-stack framework.
Small libraries are acceptable when they solve a focused problem.

Recommended packages:

- `smalot/pdfparser` for PDF text extraction
- `james-heinrich/getid3` for audio and video metadata

## Running Locally

If Apache is not set up yet, use the built-in PHP server:

```bash
php -S localhost:8000 -t public
```

## Writable Folders

These folders must be writable by PHP:

- `storage/uploads/photo`
- `storage/uploads/audio`
- `storage/uploads/pdf`
- `storage/uploads/video`
- `storage/tmp`

## Verification Checklist

- PHP version prints successfully with `php -v`
- MySQL can connect from PHP PDO
- `composer install` completes
- opening `http://localhost:8000` serves the project
- one test upload can be written to `storage/uploads/`
