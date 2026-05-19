# Mahirah - Codex Guide

**Your task:** Frontend CBR page and shared UI consistency  
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
Read mahirah-frontend-cbr-and-shared-ui.md
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
| docs/pages/browse/search-content.md                  |
| docs/pages/_shared/design-system.md                 |
| docs/pages/_shared/notes-for-all-members.md         |
| docs/pages/_shared/template-audit.md                |
| docs/planning/frontend-template-integration-plan.md |
| src/Views/layouts/main.php                          |
| src/Views/pages/search-content.php                  |
| public/assets/css/tokens.css                        |
| public/assets/css/base.css                          |
| public/assets/css/components.css                    |
| public/assets/css/utilities.css                     |
+---------------------------+--------------------------+
                            |
                            v
+------------------------------------------------------+
| STEP 2 - Paste the implementation prompt            |
|                                                      |
| "Update only the CBR page and shared frontend      |
|  styling where truly needed. Reuse the imported     |
|  template visual language and keep the project pure |
|  PHP. Do not introduce React, Vite, or backend      |
|  changes.                                           |
|                                                      |
|  Focus on:                                          |
|  - clearer CBR filter grouping                      |
|  - metadata-rich results presentation               |
|  - keeping cards, forms, tables, and spacing        |
|    visually consistent across pages                 |
|                                                      |
|  Edit only when necessary:                          |
|  - src/Views/pages/search-content.php               |
|  - public/assets/css/tokens.css                     |
|  - public/assets/css/base.css                       |
|  - public/assets/css/components.css                 |
|  - public/assets/css/utilities.css                  |
|                                                      |
|  Do not replace the shared shell. Do not change     |
|  backend contracts or route names."                 |
+---------------------------+--------------------------+
                            |
                            v
Codex generates updated PHP view file and optional shared CSS refinement
     |
     v
VERIFY before copying the code
     |
     v
Copy code into repo files
     |
     v
Open the PHP app and check CBR page plus overall styling consistency
     |
     v
git add -> git commit -> push your branch
```

---

## Verify Before Accepting Codex Output

- `search-content.php` stays a PHP view
- shared CSS changes improve consistency without breaking other pages
- no React/Vite/Node code appears
- no backend files are touched
- no route names or backend expectations change

---

## What Codex Will Get Wrong Without Context

| Codex default | Must be |
|---|---|
| Over-redesign the whole app | Refine the current template-based shell, do not replace it |
| Add frontend-only fake CBR logic | Backend truth belongs to Taufiq |
| Introduce a new CSS framework | Use the existing asset files only |
| Change shared layout and break other pages | Keep changes incremental and safe |

---

## If Codex Generates Something Wrong

Tell it exactly what is wrong. Example:

> "Do not redesign the whole shell or introduce new frameworks. Only refine the CBR page and
> shared CSS so it stays consistent with the imported template style."
