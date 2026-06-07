# Huda - Frontend: Dashboard And History

**Parent:** [Task directory](../README.md)
**Related:**
- [Reading guide](../../reading-guide.md)
- [Dashboard page spec](../../../pages/browse/dashboard.md)
- [History page spec](../../../pages/browse/history.md)

Status: Active
Type: Ownership guide

## Role

You own the frontend pages for:

- dashboard
- registration history

## Current State

Both pages are already built and wired to live backend data:

- `src/Views/pages/dashboard.php` — real: metric cards, badge distribution table, recent registrations
- `src/Views/pages/history.php` — real: registration history table with student name join

## What To Do Now

The backend is fully complete. Your job is to review and polish these pages.

- Verify metric cards display correctly with real data
- Check that the badge distribution table is easy to read
- Check that history rows are readable and the table handles many rows well
- Ensure empty states (no registrations yet, no history) are handled gracefully
- Check mobile/narrow layout if applicable
- Read the page specs to confirm nothing is missing from the design:
  - `docs/pages/browse/dashboard.md`
  - `docs/pages/browse/history.md`

## Files You Will Mainly Touch

- `src/Views/pages/dashboard.php`
- `src/Views/pages/history.php`
- shared CSS files in `public/assets/css/` if needed

## Deferred Build Spec: `/students`

This page is not currently implemented in the repo, but when Huda takes it on, it should be rebuilt to match the last approved Codex draft exactly.

- Route: add `GET /students` in `config/routes.php`
- Access: authenticated only, using the existing `auth` middleware
- Controller: create `src/Controllers/StudentsController.php`
- Model change: add `Student::getAllWithPhoto(?string $query, ?string $studentType, ?string $badge): array`
- View: create `src/Views/pages/students.php`
- Shared UI update: add the nav link to `src/Views/layouts/main.php`
- Shared CSS update: add the students gallery styles to `public/assets/css/components.css`

Exact behavior to replicate:

- Page title: `Students`
- Heading copy in the page body: `All Students ({count})`
- Show every student in a card grid with no pagination
- Filters are additive:
  - `q` for full-name search
  - `student_type` with values `'' | citizen | international`
  - `badge` with values `'' | Pendaftar | Pelajar | Aktif | Dedikasi | Cemerlang`
- Clicking a card goes to `/student-detail?id={id}`
- The sidebar link label is `Students`
- The sidebar icon used was `public/assets/images/nav/profile.png`
- The page should show an empty state card with a registration CTA when no rows match

Exact data/query contract to replicate:

- Use the existing `students` table plus `file_metadata`
- Get the latest photo per student by joining the photo row with the highest `file_metadata.id`
- Return these keys:
  - `id`
  - `full_name`
  - `ic_number`
  - `badge`
  - `student_type`
  - `photo_file_id`
  - `photo_path`
- Infer `student_type` from `ic_number` because there is no persisted `passport_number` column in the current schema:
  - `citizen` when `ic_number` matches `^[0-9]{12}$`
  - `international` otherwise

Exact layout/styling choices to replicate:

- Desktop grid: 4 cards per row
- Medium widths: 3, then 2
- Mobile: 1 per row
- Circular avatar at the top of each card
- If no photo exists, use `public/assets/images/nav/profile.png` as the placeholder avatar
- Type pill labels:
  - `Citizen`
  - `Intl`
- Keep the same dark cyan/teal visual language used by the rest of the app shell

Important:

- Do not invent a new database column for passport / international status
- Do not add pagination
- Do not change the detail route format
- Keep the implementation in pure PHP controllers/views/CSS, matching the current architecture

## Done Means

- dashboard metrics are easy to scan
- history rows are readable on desktop and mobile
- empty/no-data states are handled and not blank or broken
- pages match the spec without inventing new backend behavior
