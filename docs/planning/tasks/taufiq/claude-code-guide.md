# Taufiq - Claude Code Guide

**Your task:** Backend — core, database, uploads, and retrieval
**Your task file:** [task.md](./task.md)

---

## Full Flow

```text
PULL THE REPO
     |
     v
Read docs/README.md  ->  find your task file
     |
     v
Read taufiq-backend-core-database-and-retrieval.md
  - role
  - phase you are currently on
  - files you own
     |
     v
Open Claude Code (this session)
     |
     v
+------------------------------------------------------+
| STEP 1 - Load context into Claude Code              |
|                                                      |
| docs/architecture/erd.md                            |
| docs/implementation/system-design/01-pure-php-architecture.md
| docs/implementation/system-design/02-database-schema-and-sql-plan.md
| docs/implementation/system-design/03-registration-and-reregistration-flow.md
| docs/implementation/system-design/04-file-upload-and-storage-contract.md
| docs/implementation/system-design/05-metadata-extraction-and-analysis-rules.md
| docs/implementation/system-design/06-retrieval-and-search-contracts.md
| docs/implementation/system-design/07-badge-history-and-audit-rules.md
| docs/implementation/system-design/08-delete-and-cleanup-policy.md
+---------------------------+--------------------------+
                            |
                            v
+------------------------------------------------------+
| STEP 2 - State your current phase and target        |
|                                                      |
| Example for Phase 0:                                |
| "I am building Phase 0 of the MetaMyKad backend.   |
|  Set up the app bootstrap, PDO connection, router,  |
|  session, flash, CSRF, and error handler.           |
|  Pure PHP only. No frameworks. No Composer          |
|  packages beyond autoloading.                       |
|  Edit only src/Core/, config/, and public/index.php.|
|  Do not touch src/Views/ or any frontend file."    |
|                                                      |
| Example for Phase 1:                                |
| "I am building Phase 1 of the MetaMyKad backend.   |
|  Implement IC parsing, email category derivation,   |
|  student create, re-registration update,            |
|  registration history write, and badge compute.     |
|  Work within the contracts in 02, 03, and 07.      |
|  Edit only src/Models/, src/Controllers/,           |
|  and database/ SQL files."                          |
+---------------------------+--------------------------+
                            |
                            v
Claude Code reads, edits, and runs verification
     |
     v
VERIFY before accepting any output
     |
     v
Test the affected routes manually in the browser
     |
     v
git add -> git commit -> push your branch
```

---

## Verify Before Accepting Claude Code Output

- no framework imports or new Composer packages added without discussion
- PDO prepared statements used everywhere — no string-concatenated SQL
- `e()` used on all output in views — no raw `echo $_POST` or `echo $var`
- `CSRF.php` token verified on every POST before any controller logic runs
- no logic placed inside `public/` — only `index.php` and static assets live there
- `storage/` is the only write target for uploads and logs
- all SQL files remain split: `schema.sql`, `functions.sql`, `views.sql`, `procedures.sql`
- no frontend file under `src/Views/`, `public/assets/`, or any `.php` view is changed by backend phases

---

## Phase Scope Reminder

| Phase | What you build | Frontend unblocked |
|---|---|---|
| Phase 0 | Bootstrap, router, DB connection, session, CSRF, error handler | Nobody yet |
| Phase 1 | IC parsing, email category, student create/update, history, badge | Ammar can wire real registration |
| Phase 2 | File upload, metadata insert, PDF extraction, delete cleanup | Ammar can wire real detail page |
| Phase 3 | ABR/TBR/CBR queries, dashboard counts, history retrieval | All frontend members unblocked |

---

## What Claude Code Will Get Wrong Without Context

| Default behaviour | Must be |
|---|---|
| Suggest a framework like Laravel or Slim | Stay in pure PHP with no framework |
| Use `$_GET`/`$_POST` directly in views | Route through controllers only |
| Write SQL inline in controllers | SQL belongs in models, views, and stored procedures |
| Add new packages to composer.json freely | Discuss before adding any dependency |
| Edit view files during backend phases | Frontend files are off limits until Phase 3 is done |
| Create one big `database.sql` file | Keep schema, functions, views, procedures separate |

---

## If Claude Code Goes Off Track

Tell it exactly what constraint was broken. Example:

> "Keep this in pure PHP. Do not suggest a framework. Route all logic through the
> controller and model layer. Do not write SQL directly in the controller."

Or:

> "Do not edit any file under src/Views/ or public/assets/. This is a backend phase.
> Frontend wiring happens after Phase 3 is complete."
