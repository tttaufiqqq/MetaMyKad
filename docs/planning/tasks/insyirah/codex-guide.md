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
| "Review and polish the ABR and TBR search pages.   |
|  Both are already built and wired to live backend   |
|  data. Do not rebuild them from scratch.            |
|                                                      |
|  The backend is complete. Do not change backend     |
|  files, routes, controllers, or SQL.                |
|                                                      |
|  Focus on:                                          |
|  - clearer filter grouping and labelling            |
|  - stronger visual distinction between filters and  |
|    results area                                     |
|  - filter values must persist in the form after    |
|    a search (do not wipe inputs on submit)          |
|  - empty results state must be visible and clear   |
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
|  move search logic into JavaScript. The existing    |
|  PHP variables are correct — only improve display." |
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
- filters match the documented ABR and TBR contracts only — no new fields invented
- filter values are repopulated in the form after a search (not wiped)
- empty results state is shown clearly, not a blank area
- no React/Vite/Node code appears
- no backend files are touched
- results tables use the existing PHP variables the controller already passes

---

## What Codex Will Get Wrong Without Context

| Codex default | Must be |
|---|---|
| Add extra filters not in the docs | Stay aligned with retrieval contracts exactly |
| Put search logic in frontend JS | Backend search is complete — do not duplicate it |
| Replace PHP views with SPA-style code | Stay in PHP |
| Wipe filter form after submit | Filter values must persist across the round-trip |
| Rewrite shared shell | Keep current template-based shell |

---

## If Codex Generates Something Wrong

Tell it exactly what is wrong. Example:

> "Keep this inside the current PHP views. Do not move search logic into JavaScript.
> The backend is already complete and passes real results into the view.
> Only improve the filter layout, result table presentation, and make sure filter
> values repopulate after submit."
