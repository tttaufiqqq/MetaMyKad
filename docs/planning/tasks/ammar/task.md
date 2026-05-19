# Ammar - Frontend: Registration And Detail Pages

**Parent:** [Task directory](../README.md)
**Related:**
- [Reading guide](../../reading-guide.md)
- [Registration form page spec](../../../pages/registration/registration-form.md)
- [Re-registration page spec](../../../pages/registration/re-registration.md)
- [Student detail page spec](../../../pages/browse/student-detail.md)

Status: Active
Type: Ownership guide

## Role

You own the frontend pages for:

- registration
- re-registration
- student detail

## Current State

- `src/Views/pages/register.php` — **STUB** (card heading only, no form) — your main task
- `src/Views/pages/re-register.php` — **STUB** (card heading only, no form) — your second task
- `src/Views/pages/student-detail.php` — already real and wired to live data, no rebuild needed

## What To Do Now

The backend is fully complete. You can integrate directly — no waiting.

**For `register.php`:**
- Build the full registration form (POST to `/register`)
- Fields: `ic_number`, `full_name`, `phone`, `email`, `matric_number`, `password`, plus optional file inputs for `photo`, `audio`, `pdf`, `video`
- Include the CSRF partial: `<?php include partial('csrf'); ?>`
- Show flash error messages at the top of the form
- Repopulate fields from `$_SESSION['_old']` on validation failure (the backend sets this automatically)
- Read the page spec: `docs/pages/registration/registration-form.md`

**For `re-register.php`:**
- Same form structure but with `<input type="hidden" name="mode" value="update">`
- POST also goes to `/register` (same route, backend detects mode)
- No `matric_number` or `password` fields needed (backend skips them in update mode)
- Read the page spec: `docs/pages/registration/re-registration.md`

**For `student-detail.php`:**
- Already done. Review it for UI consistency only if needed.

## Files You Will Mainly Touch

- `src/Views/pages/register.php`
- `src/Views/pages/re-register.php`
- shared CSS files in `public/assets/css/` if needed

## Pending Build Spec: `/register` and `/re-register`

These views are currently stubs. When Ammar builds them, the output should match the last approved Codex draft exactly.

- Route: already exists — `POST /register` in `config/routes.php`
- Controller: already exists — `RegistrationController@store`
- Views to replace: `src/Views/pages/register.php`, `src/Views/pages/re-register.php`

Exact behavior for `register.php`:
- `<form action="/register" method="post">`
- Include CSRF: `<?php include partial('csrf'); ?>`
- Fields: `ic_number`, `full_name`, `phone`, `email`, `matric_number`, `password`
- Optional file inputs: `photo`, `audio`, `pdf`, `video` (each with accepted MIME hint)
- Repopulate every field from `$_SESSION['_old']` on validation failure
- Show flash error at the top if present

Exact behavior for `re-register.php`:
- Same form structure but add `<input type="hidden" name="mode" value="update">`
- Omit `matric_number` and `password` fields (backend skips them in update mode)
- Prefill `ic_number` from `$_GET['ic']` if present in the query string

Important:
- Do not change `action="/register"` — the route name is fixed
- Do not invent new backend fields or validation logic
- Do not add JS validation — server already validates, just display the flash and repopulate
- Keep the implementation in pure PHP views, matching the current architecture

## Done Means

- registration form submits correctly and shows field errors on failure
- re-registration form prefills IC if passed in query string, omits matric/password fields
- flash messages (success and error) are visible and styled
- file inputs are clearly labelled with accepted types
