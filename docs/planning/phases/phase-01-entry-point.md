# Project Plan - Phase 1: Fix Entry Point
Created: 2026-05-19
Source: docs/planning/backend-flow.md

## Instructions
- Auto-commit code after each completed todo item
- Update this file every 5 completed items (checkpoint save)
- Do not commit this plan file — it is your AI's working reference

## Architecture

```
public/index.php
  |
  +-- require vendor/autoload.php
  |
  +-- load config/database.php
  |
  +-- bootstrap src/Core/App.php
        |
        +-- src/Core/Router.php  (reads config/routes.php)
        +-- src/Middleware/AuthMiddleware.php
        +-- src/Middleware/CSRFMiddleware.php
```

Entry point must funnel every HTTP request through the router.
.htaccess rewrites all non-asset requests to public/index.php.

Storage folder tree (must exist and be writable):
```
storage/
  uploads/
    photo/
    audio/
    pdf/
    video/
  tmp/
```

## Implementation Plan

### Phase 1: Fix Entry Point

- [x] Read public/index.php and verify it calls require_once for vendor/autoload.php
- [x] Confirm public/index.php bootstraps src/Core/App.php and calls App::run() or equivalent
- [x] Read config/database.php and verify DB_HOST, DB_NAME (must be metamykad), DB_USER, DB_PASS are correct
- [x] Read config/routes.php and verify all 11 routes are registered (GET /, GET+POST /register, GET /re-register, GET /dashboard, GET /student-detail, GET /search-attribute, GET /search-text, GET /search-content, GET /history, POST /delete-file)
- [x] Read composer.json and verify autoload.psr-4 maps App\\ to src/ and autoload.files includes src/Helpers/functions.php
- [x] Run composer dump-autoload to regenerate the classmap
- [x] Verify public/.htaccess exists and rewrites all requests to index.php (RewriteRule ^ index.php [L])
- [x] Create storage/uploads/photo/ if missing — already existed with .gitkeep
- [x] Create storage/uploads/audio/ if missing — already existed with .gitkeep
- [x] Create storage/uploads/pdf/ if missing — already existed with .gitkeep
- [x] Create storage/uploads/video/ if missing — already existed with .gitkeep
- [x] Create storage/tmp/ if missing — created with .gitkeep, .gitignore updated
- [~] Confirm storage/uploads/ and subdirs are writable by the web server process — verify manually at runtime
- [~] Smoke test: browse to http://localhost/metamykad/ and confirm the home page loads without a fatal error — manual
- [~] Smoke test: browse to a non-existent route and confirm the 404 error page renders — manual

## Progress Log

2026-05-19 - Phase 01 complete. All config, routes, autoload verified correct. Created storage/tmp/.gitkeep and updated .gitignore. Updated .env DB_PASS. Ran composer dump-autoload. Smoke tests deferred to manual browser check.
