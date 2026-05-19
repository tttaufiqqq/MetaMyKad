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
Read ammar-frontend-registration-and-detail-pages.md
  - role
  - wait rule
  - files you mainly touch
     |
     v
Open Codex
     |
     v
+------------------------------------------------------+
| STEP 1 - Paste these files into Codex               |
|                                                      |
| docs/pages/registration/registration-form.md               |
| docs/pages/registration/re-registration.md                 |
| docs/pages/browse/student-detail.md                   |
| docs/pages/_shared/design-system.md                  |
| docs/pages/_shared/notes-for-all-members.md          |
| docs/pages/_shared/template-audit.md                 |
| docs/planning/frontend-template-integration-plan.md  |
| src/Views/layouts/main.php                           |
| src/Views/pages/register.php                         |
| src/Views/pages/re-register.php                      |
| src/Views/pages/student-detail.php                   |
| public/assets/css/tokens.css                         |
| public/assets/css/components.css                     |
+---------------------------+--------------------------+
                            |
                            v
+------------------------------------------------------+
| STEP 2 - Paste the implementation prompt            |
|                                                      |
| "Update only the PHP frontend pages for             |
|  registration, re-registration, and student detail. |
|  Keep the project pure PHP. Reuse the imported      |
|  template style already present in public/assets/css |
|  and do not introduce React, Vite, Node, or backend |
|  changes.                                           |
|                                                      |
|  Focus on:                                          |
|  - clearer form grouping                            |
|  - better upload tile presentation                  |
|  - cleaner derived-feedback areas                   |
|  - stronger metadata-card layout on detail page     |
|  - mobile-friendly spacing                          |
|                                                      |
|  Edit only:                                         |
|  - src/Views/pages/register.php                     |
|  - src/Views/pages/re-register.php                  |
|  - src/Views/pages/student-detail.php               |
|  - public/assets/css/components.css if needed       |
|                                                      |
|  Do not touch backend files. Do not rename routes.  |
|  Do not change form actions. Do not invent new      |
|  backend fields. Update existing files only."       |
+---------------------------+--------------------------+
                            |
                            v
Codex generates updated PHP view files and optional CSS refinement
     |
     v
VERIFY before copying the code
     |
     v
Copy code into repo files
     |
     v
Open the PHP app and check register, re-register, and detail pages
     |
     v
git add -> git commit -> push your branch
```

---

## Verify Before Accepting Codex Output

- `register.php`, `re-register.php`, and `student-detail.php` stay in PHP view format
- no React, JSX, TSX, or Vite code appears anywhere
- form actions stay exactly as they are now
- no backend file under `src/Core/`, `src/Models/`, `database/`, or `config/` is changed
- upload boxes remain visual only unless Taufiq has already provided real upload behavior
- detail page still supports delete confirmation flow without changing the backend route

---

## What Codex Will Get Wrong Without Context

| Codex default | Must be |
|---|---|
| Rebuild page in React | Stay in `src/Views/pages/*.php` |
| Add frontend-only fake business logic | Backend truth belongs to Taufiq |
| Rename routes or form actions | Keep current PHP routes exactly |
| Replace the shared shell | Build inside the current shared shell |
| Add Node dependencies | Forbidden |

---

## If Codex Generates Something Wrong

Tell it exactly what is wrong. Example:

> "Keep this as a PHP view, not React. Do not change the form action or backend fields.
> Please only improve the layout and reuse the current template-based CSS direction."
