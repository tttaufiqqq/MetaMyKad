# MetaMyKad

MetaMyKad is a pure PHP + MySQL multimedia student registry project for the Multimedia Database
course. The system registers student information using Malaysian IC data, stores optional
multimedia uploads, derives metadata automatically, and supports attribute-based, text-based,
and content-based retrieval.

## Tech Stack

- PHP 8.2+
- MySQL 8.0+
- PDO
- Plain HTML, CSS, and JavaScript
- Composer (autoloading only — no framework)

---

## Prerequisites

Make sure you have all three installed before starting:

| Tool | Check | Download if missing |
|---|---|---|
| PHP 8.2+ | `php -v` | https://www.php.net/downloads |
| Composer | `composer -V` | https://getcomposer.org |
| MySQL 8.0+ | already installed | — |

> MySQL Workbench is optional but recommended for running the SQL files.

---

## Setup (do this once)

### 1. Clone and install

```bash
git clone https://github.com/tttaufiqqq/MetaMyKad.git
cd MetaMyKad
composer install
```

> `composer install` generates the `vendor/` folder which is required to boot the app.
> The server will crash with a fatal error if you skip this step.

### 2. Create your `.env`

Copy the example file and fill in your MySQL credentials:

```bash
cp .env.example .env
```

Open `.env` and set your values:

```env
APP_NAME=MetaMyKad
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Kuala_Lumpur

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=metamykad
DB_CHARSET=utf8mb4
DB_USER=root
DB_PASS=your_mysql_password
```

> If your MySQL root has no password, leave `DB_PASS=` empty.

### 3. Create the database

Open **MySQL Workbench**, connect to your local server, then run:

```sql
CREATE DATABASE metamykad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run the SQL files in order

In MySQL Workbench, open and run each file using **File → Open SQL Script**, then click the
lightning bolt to execute. Run them in this exact order:

```
1. database/schema.sql       ← tables and indexes
2. database/functions.sql    ← MySQL functions
3. database/views.sql        ← reusable read queries
4. database/procedures.sql   ← stored procedures
```

Or run them from the terminal if you prefer:

```bash
mysql -u root -p metamykad < database/schema.sql
mysql -u root -p metamykad < database/functions.sql
mysql -u root -p metamykad < database/views.sql
mysql -u root -p metamykad < database/procedures.sql
```

### 5. Start the server

```bash
php -S localhost:8000 -t public
```

This is the equivalent of `php artisan serve` for pure PHP projects.
The `-t public` flag tells PHP to serve from the `public/` folder as the web root.

Open your browser at:

```
http://localhost:8000
```

---

## Daily Workflow

Every time you want to work on the project:

```bash
# 1. pull latest changes
git pull

# 2. install any new packages (if composer.json changed)
composer install

# 3. start the server
php -S localhost:8000 -t public
```

That's it. No XAMPP, no Apache, no Nginx needed.

---

## Project Structure

```text
metamykad/
├── public/              # Web root — only this folder is served
│   ├── index.php        # Front controller (all requests go here)
│   └── assets/          # CSS, JS, images
├── src/
│   ├── Core/            # App, Router, Database, Session, CSRF, Validator
│   ├── Controllers/     # Request handlers
│   ├── Models/          # Database query logic
│   ├── Middleware/      # Auth and CSRF guards
│   ├── Views/           # PHP page templates
│   └── Helpers/         # Global helper functions
├── config/
│   ├── app.php          # App settings
│   ├── database.php     # DB connection config
│   └── routes.php       # All route definitions
├── database/
│   ├── schema.sql       # Tables and indexes
│   ├── functions.sql    # MySQL functions
│   ├── views.sql        # MySQL views
│   └── procedures.sql   # Stored procedures
├── storage/
│   ├── logs/            # App error logs
│   └── uploads/         # Uploaded files (photo, audio, pdf, video)
├── docs/                # Project documentation
├── .env.example         # Environment template
└── composer.json
```

---

## Routes

All requests go through `public/index.php` and are dispatched via `config/routes.php`.

| Method | Path | Purpose |
|---|---|---|
| GET | `/` | Home |
| GET/POST | `/register` | New registration |
| GET | `/re-register` | Re-registration form |
| GET | `/dashboard` | Dashboard overview |
| GET | `/student-detail` | Single student view |
| GET | `/search-attribute` | ABR search |
| GET | `/search-text` | TBR search |
| GET | `/search-content` | CBR search |
| GET | `/history` | Registration history |
| POST | `/delete-file` | Delete a file record |

---

## Troubleshooting

**`Failed to open stream: vendor/autoload.php` or `Class not found` on boot**
Run `composer install` first. This generates the `vendor/` folder — the app cannot start without it.

**`Connection refused` or DB error**
- Confirm MySQL Server is running
- Check `DB_USER`, `DB_PASS`, and `DB_NAME` in your `.env`
- Make sure the `metamykad` database was created

**`404` on every page**
The built-in PHP server does not use `.htaccess`. The `-t public` flag handles routing instead.
Always start the server with `php -S localhost:8000 -t public`, not from the project root.

**`Permission denied` on file upload**
Make sure the storage folders are writable:

```bash
# Windows — right-click storage/ → Properties → Security → allow write
# or via Git Bash:
chmod -R 775 storage/
```

---

## Documentation

Full project documentation is in [`docs/`](./docs/README.md).

Recommended reading order:

1. [`docs/planning/reading-guide.md`](./docs/planning/reading-guide.md)
2. [`docs/product/prd.md`](./docs/product/prd.md)
3. [`docs/architecture/erd.md`](./docs/architecture/erd.md)
4. [`docs/implementation/system-design/README.md`](./docs/implementation/system-design/README.md)
