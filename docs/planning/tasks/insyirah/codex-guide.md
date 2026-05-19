# Insyirah - Codex Guide

**Your task:** Frontend ABR and TBR pages  
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
Read insyirah-frontend-abr-and-tbr-pages.md
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
| docs/pages/browse/search-attribute.md                |
| docs/pages/browse/search-text.md                     |
| docs/implementation/system-design/                  |
|   06-retrieval-and-search-contracts.md              |
| docs/pages/_shared/design-system.md                 |
| docs/pages/_shared/notes-for-all-members.md         |
| docs/pages/_shared/template-audit.md                |
| docs/planning/frontend-template-integration-plan.md |
| src/Views/pages/search-attribute.php                |
| src/Views/pages/search-text.php                     |
| public/assets/css/components.css                    |
+---------------------------+--------------------------+
                            |
                            v
+------------------------------------------------------+
| STEP 2 - Paste the implementation prompt            |
|                                                      |
| "Update only the ABR and TBR pages in the current  |
|  PHP frontend. Reuse the imported template filter   |
|  and results-table styling. Keep the project pure   |
|  PHP and do not change backend logic.               |
|                                                      |
|  Focus on:                                          |
|  - clearer filter grouping                          |
|  - stronger visual distinction between filters and  |
|    results                                          |
|  - compact, readable result tables                 |
|  - filter-state friendliness on mobile              |
|                                                      |
|  Edit only:                                         |
|  - src/Views/pages/search-attribute.php             |
|  - src/Views/pages/search-text.php                  |
|  - public/assets/css/components.css if needed       |
|                                                      |
|  Do not invent new search fields beyond the docs.   |
|  Do not change routes, backend expectations, or     |
|  move search logic into JavaScript."                |
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
Open the PHP app and check ABR and TBR pages
     |
     v
git add -> git commit -> push your branch
```

---

## Verify Before Accepting Codex Output

- pages remain PHP views
- filters match the documented ABR and TBR contracts only
- no new backend expectations are invented
- no React/Vite/Node code appears
- no backend files are touched
- results tables remain ready for real query results from Taufiq

---

## What Codex Will Get Wrong Without Context

| Codex default | Must be |
|---|---|
| Add extra filters not in the docs | Stay aligned with retrieval contracts |
| Put search logic in frontend JS | Backend search belongs to Taufiq |
| Replace PHP views with SPA-style code | Stay in PHP |
| Rewrite shared shell | Keep current template-based shell |

---

## If Codex Generates Something Wrong

Tell it exactly what is wrong. Example:

> "Keep this inside the current PHP views. Do not move search logic into JavaScript.
> Only improve the filter layout and result table presentation."
