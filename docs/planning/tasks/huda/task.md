# Huda - Frontend: Dashboard And History

**Parent:** [Task directory](../README.md)
**Related:**
- [Reading guide](../../reading-guide.md)
- [Dashboard page spec](../../../pages/browse/dashboard.md)
- [History page spec](../../../pages/browse/history.md)

Status: Draft  
Type: Ownership guide

## Role

You own the frontend pages for:

- dashboard
- registration history

## Wait Rule

Do not do real backend integration until Taufiq Phase 3 is complete.

Before that, you may:

- improve card layout
- improve table layout
- prepare empty and no-data states

## Files You Will Mainly Touch

- `src/Views/pages/dashboard.php`
- `src/Views/pages/history.php`
- shared CSS files in `public/assets/css/` if needed

## Done Means

- dashboard metrics are easy to scan
- history rows are readable on desktop and mobile
- both pages can consume real backend-backed data without structural rewrites
