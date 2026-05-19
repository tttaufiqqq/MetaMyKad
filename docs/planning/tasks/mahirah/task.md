# Mahirah - Frontend: CBR And Shared UI

**Parent:** [Task directory](../README.md)
**Related:**
- [Reading guide](../../reading-guide.md)
- [CBR page spec](../../../pages/browse/search-content.md)
- [Design system](../../../pages/_shared/design-system.md)
- [Shared page notes](../../../pages/_shared/notes-for-all-members.md)

Status: Draft  
Type: Ownership guide

## Role

You own:

- the content-based retrieval page
- shared UI consistency across frontend pages

## Wait Rule

Do not do real backend integration for CBR until Taufiq Phase 3 is complete.

Before that, you may:

- improve the shared layout shell
- keep buttons, forms, cards, and tables visually consistent
- prepare the CBR filter layout

## Files You Will Mainly Touch

- `src/Views/pages/search-content.php`
- shared layout and partial view files only when needed
- shared CSS files in `public/assets/css/`

## Done Means

- CBR filters and results are clear
- shared UI styles stay consistent across all pages
- frontend polish does not invent backend behavior
