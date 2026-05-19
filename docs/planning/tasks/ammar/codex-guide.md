# Ammar - Codex Guide

**Your task:** Frontend registration, re-registration, and student detail pages  
**Your task file:** [task.md](./task.md)

---

## Files to Paste into Codex

```
docs/pages/registration/registration-form.md
docs/pages/registration/re-registration.md
docs/pages/_shared/design-system.md
docs/pages/_shared/notes-for-all-members.md
docs/pages/_shared/feedback-and-dialogs.md
src/Views/layouts/main.php
src/Views/partials/csrf.php
src/Views/partials/toast.php
src/Views/pages/register.php
src/Views/pages/re-register.php
public/assets/css/tokens.css
public/assets/css/components.css
```

## Prompt

> "Build the PHP view for register.php and re-register.php. Both are currently stubs with only
> a card heading and no form. Replace them with complete working forms.
>
> The backend is already complete. Write views that connect to it exactly as-is.
> Do not change any backend files, routes, or field names.
>
> For register.php:
> - POST action="/register" method="post"
> - Include CSRF: `<?php include partial('csrf'); ?>`
> - Fields: ic_number, full_name, phone, email, matric_number, password
> - Optional file inputs: photo, audio, pdf, video
> - Repopulate fields from `$_SESSION['_old']` on validation failure
> - Show flash error at top if present
>
> For re-register.php:
> - Same form but add hidden: name=mode value=update
> - No matric_number or password fields
> - Prefill ic_number from `$_GET['ic']` if present
>
> Keep project pure PHP. Reuse existing CSS classes. Do not introduce React, Vite, or Node.
>
> Edit only:
> - src/Views/pages/register.php
> - src/Views/pages/re-register.php
> - public/assets/css/components.css if truly needed
>
> Do not touch: backend files, routes, controllers, student-detail.php (already done)."

---

## Verify Before Accepting Codex Output

- `register.php` and `re-register.php` are PHP views with real working forms, not stubs
- CSRF partial is included in both forms
- form action is `action="/register"` — not changed, not invented
- `register.php` includes: ic_number, full_name, phone, email, matric_number, password, and file inputs
- `re-register.php` has hidden `mode=update`, no matric_number or password fields
- fields repopulate from `$_SESSION['_old']` on validation failure
- no React, JSX, TSX, or Vite code appears anywhere
- no backend file under `src/Core/`, `src/Models/`, `database/`, or `config/` is changed
- `student-detail.php` is not touched (it is already done)

---

## What Codex Will Get Wrong Without Context

| Codex default | Must be |
|---|---|
| Rebuild page in React | Stay in `src/Views/pages/*.php` |
| Invent new backend fields or routes | Use only what the backend already expects |
| Rename routes or form actions | `action="/register"` — exactly as-is |
| Build modal/JS validation instead of server-side | Server already validates; just display flash + repopulate |
| Replace the shared shell | Build inside the current shared shell |
| Add Node dependencies | Forbidden |

---

## If Codex Generates Something Wrong

Tell it exactly what is wrong. Example:

> "Keep this as a PHP view, not React. Do not change the form action or backend fields.
> The backend is already complete — I only need the view layer. Do not add JS validation logic;
> server validation is already handled. Just build the form markup and repopulate from _old."

---

## Future Replica Prompt: `/register` and `/re-register`

If Ammar needs to rebuild these forms from scratch in a new Codex session, use this exact prompt:

> "Build the registration and re-registration forms in the existing pure PHP app based on
> `docs/pages/registration/registration-form.md` and `docs/pages/registration/re-registration.md`.
> Keep it in the current architecture and visual system.
>
> Implement exactly these files:
> - `src/Views/pages/register.php` — replace the current stub with a complete form
> - `src/Views/pages/re-register.php` — replace the current stub with a complete form
>
> Exact behavior for register.php:
> - `<form action="/register" method="post">`
> - Include CSRF: `<?php include partial('csrf'); ?>`
> - Fields: ic_number, full_name, phone, email, matric_number, password
> - Optional file inputs: photo, audio, pdf, video
> - Repopulate every field from `$_SESSION['_old']` on validation failure
> - Show flash error at the top if present
>
> Exact behavior for re-register.php:
> - Same form but with `<input type="hidden" name="mode" value="update">`
> - Omit matric_number and password fields
> - Prefill ic_number from `$_GET['ic']` if present
> - POST to the same `/register` route
>
> Stay in PHP/CSS only. Do not add React, new frameworks, or backend changes.
> Do not change any backend files, routes, controllers, or field names."
