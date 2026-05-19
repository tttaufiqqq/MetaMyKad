# Huda - Codex Guide

**Your task:** Frontend dashboard and history pages  
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
Read huda-frontend-dashboard-and-history.md
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
| docs/pages/browse/dashboard.md                        |
| docs/pages/browse/history.md                          |
| docs/pages/_shared/design-system.md                  |
| docs/pages/_shared/notes-for-all-members.md          |
| docs/pages/_shared/template-audit.md                 |
| docs/planning/frontend-template-integration-plan.md  |
| src/Views/layouts/main.php                           |
| src/Views/pages/dashboard.php                        |
| src/Views/pages/history.php                          |
| public/assets/css/tokens.css                         |
| public/assets/css/components.css                     |
+---------------------------+--------------------------+
                            |
                            v
+------------------------------------------------------+
| STEP 2 - Paste the implementation prompt            |
|                                                      |
| "Update only the dashboard and history pages in the |
|  current PHP frontend. Reuse the imported template  |
|  dashboard style. Keep the app pure PHP and do not  |
|  introduce React, Vite, or backend changes.         |
|                                                      |
|  Focus on:                                          |
|  - stronger metric card hierarchy                   |
|  - better table readability                         |
|  - clearer recent activity / history presentation   |
|  - empty-state and placeholder polish               |
|  - mobile-friendly spacing                          |
|                                                      |
|  Edit only:                                         |
|  - src/Views/pages/dashboard.php                    |
|  - src/Views/pages/history.php                      |
|  - public/assets/css/components.css if needed       |
|                                                      |
|  Do not change backend contracts, route names, or   |
|  SQL expectations. Update existing files only."     |
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
Open the PHP app and check dashboard and history pages
     |
     v
git add -> git commit -> push your branch
```

---

## Verify Before Accepting Codex Output

- pages stay in `src/Views/pages/*.php`
- no backend file is touched
- no React/Vite code appears
- dashboard still expects real MySQL metrics later rather than hardcoded fake logic
- history table remains ready for `registration_history` data
- shared shell in `main.php` is not replaced

---

## What Codex Will Get Wrong Without Context

| Codex default | Must be |
|---|---|
| Add new frontend framework code | Stay in PHP views and CSS only |
| Turn metrics into fake JS-only widgets | Keep placeholders ready for backend data |
| Rewrite navigation layout | Keep the current template-based shell |
| Move data logic into frontend | Backend truth belongs to Taufiq |

---

## If Codex Generates Something Wrong

Tell it exactly what is wrong. Example:

> "Do not add React or fake dashboard logic. Keep this as PHP view markup and only improve the
> card layout, table readability, and template-style polish."
