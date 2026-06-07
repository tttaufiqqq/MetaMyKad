# Huda - Codex Guide

**Your task:** Frontend dashboard and history pages  
**Your task file:** [task.md](./task.md)

---

## Files to Paste into Codex

```
docs/pages/browse/dashboard.md
docs/pages/browse/history.md
docs/pages/_shared/design-system.md
docs/pages/_shared/notes-for-all-members.md
docs/pages/_shared/template-audit.md
docs/planning/frontend-template-integration-plan.md
src/Views/layouts/main.php
src/Views/pages/dashboard.php
src/Views/pages/history.php
public/assets/css/tokens.css
public/assets/css/components.css
```

## Prompt

> "Make the following specific improvements to dashboard.php and history.php.
> The backend is already complete — do not change any backend files, routes, controllers, or SQL.
> Do not change variable names. The PHP variables passed to the views are correct.
>
> In dashboard.php:
>
> 1. The `.dashboard-grid` class does not exist in components.css. Replace it with a two-column
>    layout using two `.table-card` sections inside a wrapping div styled inline or using an
>    existing layout class. Both the badge table and recent table should sit side-by-side on
>    desktop and stack on mobile.
>
> 2. In the Recent Registrations table, add the badge as a styled pill. Apply the existing
>    `.tag-pill` CSS class to each badge value instead of displaying it as plain text.
>
> 3. In the Badge Distribution table, add a link on each badge row that goes to
>    `/search-attribute?badge={badge}&_search=1` so staff can click a badge to see those students.
>
> 4. Add a result count to the Recent Registrations heading:
>    `Recent Registrations (<?= count($recentRows) ?>)`.
>
> 5. Remove all `style="padding:1rem;"` inline styles. Replace with a `p` that uses the existing
>    `.text-dim` class, or wrap in a `<div class="card">`.
>
> In history.php:
>
> 6. Add a "View" link column at the end of each history row. Link to
>    `/search-attribute?_search=1&ic_number=<?= urlencode($row['ic_number']) ?>` so staff can
>    look up that student. Use the existing `.button` class, styled small.
>
> 7. Replace the plain `<p class="muted" style="padding:1rem;">` empty state with a proper
>    styled card: a `<div class="card">` containing a heading and a short message.
>
> 8. Add a result count to the History Log heading: `History Log (<?= count($rows) ?> entries)`.
>
> Edit only:
> - src/Views/pages/dashboard.php
> - src/Views/pages/history.php
> - public/assets/css/components.css only if a needed class is truly missing
>
> Do not rebuild either page from scratch. Make only the changes listed above."

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
