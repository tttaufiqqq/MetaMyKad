# Mahirah - Frontend: CBR And Shared UI

**Parent:** [Task directory](../README.md)
**Related:**
- [Reading guide](../../reading-guide.md)
- [CBR page spec](../../../pages/browse/search-content.md)
- [Design system](../../../pages/_shared/design-system.md)
- [Shared page notes](../../../pages/_shared/notes-for-all-members.md)

Status: Active
Type: Ownership guide

## Role

You own:

- the content-based retrieval page (CBR)
- shared UI consistency across all frontend pages

## Current State

- `src/Views/pages/search-content.php` — already real and wired to live backend data: CBR filter form + results table

## What To Do Now

The backend is fully complete. Your job is to review the CBR page and audit shared UI consistency.

**For `search-content.php`:**
- Verify CBR filter fields match what the backend accepts (file type, photo category, audio duration tier, video resolution tier, etc.)
- Check that results table shows the right metadata columns per file type
- Ensure empty results state (no matches found) is handled and not blank
- Check that filter values persist in the form after a search
- Read the page spec: `docs/pages/browse/search-content.md`
- Read the retrieval contracts: `docs/implementation/system-design/06-retrieval-and-search-contracts.md`

**For shared UI consistency:**
- Review all pages for visual consistency: buttons, forms, cards, tables, spacing
- Check that toast/flash messages look and behave the same across all pages
- Check that the confirm-dialog (used for file delete) works correctly
- Refer to: `docs/pages/_shared/design-system.md` and `docs/pages/_shared/notes-for-all-members.md`

## Files You Will Mainly Touch

- `src/Views/pages/search-content.php`
- `src/Views/layouts/main.php` and partials only when needed
- shared CSS files in `public/assets/css/`

## Done Means

- CBR filters match the backend contract and values persist after search
- CBR results table shows relevant metadata per file type
- empty state is visible and not just a blank table
- shared UI (buttons, cards, tables, toasts) is visually consistent across all pages
- no invented backend behavior in any frontend change
