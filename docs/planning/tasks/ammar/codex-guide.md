# Ammar - Codex Guide

**Your task:** Frontend registration, re-registration, and student detail pages  
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
Read task.md
  - role
  - current state
  - what to do now
     |
     v
Open Codex
     |
     v
+------------------------------------------------------+
| STEP 1 - Paste these files into Codex               |
|                                                      |
| docs/pages/registration/registration-form.md        |
| docs/pages/registration/re-registration.md          |
| docs/pages/_shared/design-system.md                 |
| docs/pages/_shared/notes-for-all-members.md         |
| docs/pages/_shared/feedback-and-dialogs.md          |
| src/Views/layouts/main.php                          |
| src/Views/partials/csrf.php                         |
| src/Views/partials/toast.php                        |
| src/Views/pages/register.php                        |
| src/Views/pages/re-register.php                     |
| public/assets/css/tokens.css                        |
| public/assets/css/components.css                    |
+---------------------------+--------------------------+
                            |
                            v
+------------------------------------------------------+
| STEP 2 - Paste the implementation prompt            |
|                                                      |
| "Build the PHP view for register.php and            |
|  re-register.php. Both are currently stubs with     |
|  only a card heading and no form. Replace them      |
|  with complete working forms.                       |
|                                                      |
|  The backend is already complete. Write views that  |
|  connect to it exactly as-is. Do not change any    |
|  backend files, routes, or field names.             |
|                                                      |
|  For register.php:                                  |
|  - POST action="/register" method="post"            |
|  - Include CSRF: <?php include partial('csrf'); ?>  |
|  - Fields: ic_number, full_name, phone, email,      |
|    matric_number, password                          |
|  - Optional file inputs: photo, audio, pdf, video   |
|  - Repopulate fields from $_SESSION['_old'] on      |
|    validation failure                               |
|  - Show flash error at top if present               |
|                                                      |
|  For re-register.php:                               |
|  - Same form but add hidden: name=mode value=update |
|  - No matric_number or password fields              |
|  - Prefill ic_number from $_GET['ic'] if present    |
|                                                      |
|  Keep project pure PHP. Reuse existing CSS classes. |
|  Do not introduce React, Vite, or Node.             |
|                                                      |
|  Edit only:                                         |
|  - src/Views/pages/register.php                     |
|  - src/Views/pages/re-register.php                  |
|  - public/assets/css/components.css if truly needed |
|                                                      |
|  Do not touch: backend files, routes, controllers,  |
|  student-detail.php (already done)."                |
+---------------------------+--------------------------+
                            |
                            v
Codex generates the PHP view files and optional CSS refinement
     |
     v
VERIFY before copying the code
     |
     v
Copy code into repo files
     |
     v
Open the PHP app and test register and re-register pages end to end
     |
     v
git add -> git commit -> push your branch
```

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
