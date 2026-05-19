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
| "Review and polish the dashboard and history pages. |
|  Both are already built and wired to live backend   |
|  data. Do not rebuild them from scratch.            |
|                                                      |
|  The backend is complete. Do not change backend     |
|  files, routes, controllers, or SQL.                |
|                                                      |
|  Focus on:                                          |
|  - stronger metric card hierarchy                   |
|  - better table readability                         |
|  - clearer recent activity / history presentation   |
|  - empty-state handling (no data yet shown clearly) |
|  - mobile-friendly spacing                          |
|                                                      |
|  Edit only:                                         |
|  - src/Views/pages/dashboard.php                    |
|  - src/Views/pages/history.php                      |
|  - public/assets/css/components.css if needed       |
|                                                      |
|  Do not change variable names, backend contracts,   |
|  route names, or SQL expectations. The existing     |
|  PHP variables passed to the view are correct —     |
|  only improve how they are displayed."              |
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
- dashboard uses the existing PHP variables passed from the controller — not hardcoded fake data
- history table renders rows from the existing `$history` variable — not reinvented
- shared shell in `main.php` is not replaced
- empty-state handling is visible when there is no data

---

## What Codex Will Get Wrong Without Context

| Codex default | Must be |
|---|---|
| Add new frontend framework code | Stay in PHP views and CSS only |
| Replace PHP variables with hardcoded fake data | Use the existing variables the controller already passes |
| Rewrite navigation layout | Keep the current template-based shell |
| Move data logic into frontend | Backend is complete — do not invent logic in the view |

---

## If Codex Generates Something Wrong

Tell it exactly what is wrong. Example:

> "Do not add React or invent fake data. The backend is already complete and passes real data
> into the view. Keep this as PHP view markup and only improve the card layout, table readability,
> and empty-state handling."

---

## Future Replica Prompt: `/students`

If Huda is asked to implement the all-students gallery later, use this exact prompt:

> "Build the missing `/students` page in the existing pure PHP app based on `docs/pages/browse/students.md`.
> Keep it in the current architecture and visual system.
>
> Implement exactly these files:
> - `config/routes.php` add `GET /students` with `auth` middleware
> - `src/Controllers/StudentsController.php`
> - `src/Models/Student.php` add `getAllWithPhoto(?string $query, ?string $studentType, ?string $badge): array`
> - `src/Views/pages/students.php`
> - `src/Views/layouts/main.php` add the sidebar nav item
> - `public/assets/css/components.css` add the gallery/filter styles
>
> Exact behavior:
> - page title `Students`
> - page heading `All Students ({count})`
> - show all students as cards with no pagination
> - filters use query params `q`, `student_type`, and `badge`
> - filter logic is additive
> - card click goes to `/student-detail?id={id}`
> - sidebar item label is `Students`
> - use `public/assets/images/nav/profile.png` for the sidebar icon and placeholder avatar
>
> Exact data rules:
> - latest photo only, based on the highest `file_metadata.id` where `file_type = 'photo'`
> - return `id`, `full_name`, `ic_number`, `badge`, `student_type`, `photo_file_id`, `photo_path`
> - infer `student_type` from `ic_number`:
>   - `citizen` if it matches `^[0-9]{12}$`
>   - `international` otherwise
> - do not invent a new passport column
>
> Exact layout rules:
> - 4 cards per row on desktop
> - 3 then 2 on medium widths
> - 1 on mobile
> - circular avatar
> - `Citizen` and `Intl` pills
> - empty-state card with registration CTA
>
> Stay in PHP/CSS only. Do not add pagination, React, new frameworks, or schema changes."
