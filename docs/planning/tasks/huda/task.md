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

## Done Means

- dashboard metrics are easy to scan
- history rows are readable on desktop and mobile
- empty/no-data states are handled and not blank or broken
- pages match the spec without inventing new backend behavior
