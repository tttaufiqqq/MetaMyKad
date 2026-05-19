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
| "Review and polish the CBR page and audit shared   |
|  UI consistency across all frontend pages.          |
|  The CBR page is already built and wired to live    |
|  backend data. Do not rebuild it from scratch.      |
|                                                      |
|  The backend is complete. Do not change backend     |
|  files, routes, controllers, or SQL.                |
|                                                      |
|  For search-content.php:                            |
|  - Verify CBR filters match backend (file_type,     |
|    photo_category, audio_duration_tier,             |
|    video_resolution_tier)                           |
|  - Filter values must persist after a search        |
|  - Empty results state must be visible and clear   |
|  - Results table should show relevant metadata      |
|    per file type                                    |
|                                                      |
|  For shared UI:                                     |
|  - Check buttons, cards, forms, tables are          |
|    visually consistent across all pages             |
|  - Keep changes incremental — do not redesign       |
|                                                      |
|  Edit only when necessary:                          |
|  - src/Views/pages/search-content.php               |
|  - public/assets/css/tokens.css                     |
|  - public/assets/css/base.css                       |
|  - public/assets/css/components.css                 |
|  - public/assets/css/utilities.css                  |
|                                                      |
|  Do not replace the shared shell. Do not change     |
|  backend contracts, variable names, or routes.      |
|  The existing PHP variables are correct."           |
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
- CBR filters match the backend contract — no invented fields
- filter values persist in the form after a search
- empty results state is handled and visible
- shared CSS changes improve consistency without breaking other pages
- no React/Vite/Node code appears
- no backend files are touched
- no route names, variable names, or backend expectations change

---

## What Codex Will Get Wrong Without Context

| Codex default | Must be |
|---|---|
| Over-redesign the whole app | Refine the current template-based shell, do not replace it |
| Add frontend-only fake CBR logic | Backend is complete — do not invent logic in the view |
| Introduce a new CSS framework | Use the existing asset files only |
| Wipe filter form after submit | Filter values must persist across the round-trip |
| Change shared layout and break other pages | Keep changes incremental and safe |

---

## If Codex Generates Something Wrong

Tell it exactly what is wrong. Example:

> "Do not redesign the whole shell or introduce new frameworks. The backend is already complete
> and passes real data into the view. Only refine the CBR page display, ensure filter values
> persist after submit, and keep shared CSS consistent across all pages."

---

## Future Replica Prompt: `/search-content`

If Mahirah needs to rebuild the CBR page from scratch in a new Codex session, use this exact prompt:

> "Build the CBR search page in the existing pure PHP app based on
> `docs/pages/browse/search-content.md` and
> `docs/implementation/system-design/06-retrieval-and-search-contracts.md`.
> Keep it in the current architecture and visual system.
>
> Implement exactly this file:
> - `src/Views/pages/search-content.php`
>
> Exact behavior:
> - GET form with CBR filters: file_type, photo_category, audio_duration_tier, video_resolution_tier
> - Show only the filters that are relevant to the selected file_type
> - Filter values must persist in the form after submit
> - Results table shows relevant metadata columns per file type
> - Empty results state must be visible and not a blank table
>
> Exact shared UI rules:
> - Buttons, cards, forms, and tables must be visually consistent with all other pages
> - Do not redesign the shared shell or navigation
> - Keep CSS changes incremental — only add what is needed for the CBR page
>
> Stay in PHP/CSS only. Do not add pagination, React, new frameworks, or schema changes.
> Do not change routes, backend contracts, variable names, or the shared shell in main.php."
