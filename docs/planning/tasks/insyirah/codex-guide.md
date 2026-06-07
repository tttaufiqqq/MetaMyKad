# Insyirah - Codex Guide

**Your task:** Frontend ABR and TBR pages  
**Your task file:** [task.md](./task.md)

---

## Files to Paste into Codex

```
docs/pages/browse/search-attribute.md
docs/pages/browse/search-text.md
docs/implementation/system-design/06-retrieval-and-search-contracts.md
docs/pages/_shared/design-system.md
docs/pages/_shared/notes-for-all-members.md
docs/pages/_shared/template-audit.md
docs/planning/frontend-template-integration-plan.md
src/Views/pages/search-attribute.php
src/Views/pages/search-text.php
public/assets/css/components.css
```

## Prompt

> "Make the following specific improvements to search-attribute.php and search-text.php.
> The backend is already complete — do not change any backend files, routes, controllers, or SQL.
> Do not change variable names. The PHP variables passed to the views are correct.
>
> In search-attribute.php:
>
> 1. The `.filter-summary` CSS class does not exist. Replace the active filters block with
>    individual `.tag-pill` spans — one pill per active filter — instead of a plain text string.
>    Example: `<span class="tag-pill">gender = M</span>`. Add a "Clear" link next to the pills
>    that goes to `/search-attribute` with no query string.
>
> 2. Add a result count to the Results heading when results are present:
>    `Results (<?= count($results) ?> found)`.
>
> 3. In the results table, wrap each badge value in `<span class="tag-pill">` instead of
>    displaying it as plain text.
>
> 4. Remove all inline `style=` attributes. Replace with appropriate existing CSS classes
>    (`.text-dim`, `.card`, padding from `.table-card`).
>
> In search-text.php:
>
> 5. The TBR page has no active filter summary at all. Add one below the form: when `$keyword`
>    is not empty after submit, show it as a `.tag-pill` with a "Clear" link to `/search-text`.
>
> 6. Add a result count to the Results heading when results are present:
>    `Results (<?= count($results) ?> found)`.
>
> 7. Remove any inline `style=` attributes from both files.
>
> Edit only:
> - src/Views/pages/search-attribute.php
> - src/Views/pages/search-text.php
> - public/assets/css/components.css only if a needed class is truly missing
>
> Do not rebuild either page from scratch. Make only the changes listed above."

---

## Verify Before Accepting Codex Output

- pages remain PHP views
- filters match the documented ABR and TBR contracts only — no new fields invented
- filter values are repopulated in the form after a search (not wiped)
- empty results state is shown clearly, not a blank area
- no React/Vite/Node code appears
- no backend files are touched
- results tables use the existing PHP variables the controller already passes

---

## What Codex Will Get Wrong Without Context

| Codex default | Must be |
|---|---|
| Add extra filters not in the docs | Stay aligned with retrieval contracts exactly |
| Put search logic in frontend JS | Backend search is complete — do not duplicate it |
| Replace PHP views with SPA-style code | Stay in PHP |
| Wipe filter form after submit | Filter values must persist across the round-trip |
| Rewrite shared shell | Keep current template-based shell |

---

## If Codex Generates Something Wrong

Tell it exactly what is wrong. Example:

> "Keep this inside the current PHP views. Do not move search logic into JavaScript.
> The backend is already complete and passes real results into the view.
> Only improve the filter layout, result table presentation, and make sure filter
> values repopulate after submit."

---

## Future Replica Prompt: `/search-attribute` and `/search-text`

If Insyirah needs to rebuild these pages from scratch in a new Codex session, use this exact prompt:

> "Build the ABR and TBR search pages in the existing pure PHP app based on
> `docs/pages/browse/search-attribute.md`, `docs/pages/browse/search-text.md`,
> and `docs/implementation/system-design/06-retrieval-and-search-contracts.md`.
> Keep it in the current architecture and visual system.
>
> Implement exactly these files:
> - `src/Views/pages/search-attribute.php`
> - `src/Views/pages/search-text.php`
>
> Exact behavior for search-attribute.php:
> - GET form with filters: gender, state, age range, file type present
> - Filter values must persist in the form after submit
> - Results table shows: full name, IC number, state, gender, badge, file types present
> - Empty results state must be visible and not a blank table
>
> Exact behavior for search-text.php:
> - GET form with: keyword input, optional tag filter
> - MATCH AGAINST fulltext search is handled by backend — do not replicate it in JS
> - Filter values must persist in the form after submit
> - Results table shows: file name, student name, file type, extracted text excerpt
> - Empty results state must be visible
>
> Stay in PHP/CSS only. Do not move search logic into JavaScript.
> Do not change routes, backend expectations, or PHP variable names."
