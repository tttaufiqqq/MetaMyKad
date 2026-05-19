# Insyirah - Frontend: ABR And TBR Pages

**Parent:** [Task directory](../README.md)
**Related:**
- [Reading guide](../../reading-guide.md)
- [ABR page spec](../../../pages/browse/search-attribute.md)
- [TBR page spec](../../../pages/browse/search-text.md)
- [Retrieval contracts](../../../implementation/system-design/06-retrieval-and-search-contracts.md)

Status: Active
Type: Ownership guide

## Role

You own the frontend pages for:

- attribute-based retrieval (ABR)
- text-based retrieval (TBR)

## Current State

Both pages are already built and wired to live backend data:

- `src/Views/pages/search-attribute.php` — real: ABR filter form + results table
- `src/Views/pages/search-text.php` — real: TBR keyword form + results table

## What To Do Now

The backend is fully complete. Your job is to review and polish these pages.

- Verify all ABR filter fields match what the backend accepts (gender, state, age range, file type)
- Verify TBR keyword input and optional tag filter work correctly
- Check that results tables show the right columns clearly
- Ensure empty results state (no matches found) is handled and not blank or broken
- Check that filter values are preserved in the form after a search (no wiping inputs on submit)
- Read the page specs and retrieval contracts to confirm nothing is missing:
  - `docs/pages/browse/search-attribute.md`
  - `docs/pages/browse/search-text.md`
  - `docs/implementation/system-design/06-retrieval-and-search-contracts.md`

## Files You Will Mainly Touch

- `src/Views/pages/search-attribute.php`
- `src/Views/pages/search-text.php`
- shared CSS files in `public/assets/css/` if needed

## Done Means

- filters are understandable and labelled clearly
- filter values persist in the form after a search
- results layout handles real result sets including many rows
- empty state is visible and not just a blank table
- page structure matches the backend search contract without reinvention
