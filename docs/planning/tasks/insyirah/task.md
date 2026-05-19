# Insyirah - Frontend: ABR And TBR Pages

**Parent:** [Task directory](../README.md)
**Related:**
- [Reading guide](../../reading-guide.md)
- [ABR page spec](../../../pages/browse/search-attribute.md)
- [TBR page spec](../../../pages/browse/search-text.md)
- [Retrieval contracts](../../../implementation/system-design/06-retrieval-and-search-contracts.md)

Status: Draft  
Type: Ownership guide

## Role

You own the frontend pages for:

- attribute-based retrieval
- text-based retrieval

## Wait Rule

Do not do real backend integration until Taufiq Phase 3 is complete.

Before that, you may:

- refine search form layouts
- refine results table layouts
- make filter state and empty state clear

## Files You Will Mainly Touch

- `src/Views/pages/search-attribute.php`
- `src/Views/pages/search-text.php`
- shared CSS files in `public/assets/css/` if needed

## Done Means

- filters are understandable
- results layout can handle real result sets
- page structure matches the backend search contract without reinvention
