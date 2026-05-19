# Mahirah - Codex Guide

**Your task:** Frontend CBR page and shared UI consistency  
**Your task file:** [task.md](./task.md)

---

## Files to Paste into Codex

```
docs/pages/browse/search-content.md
docs/pages/_shared/design-system.md
docs/pages/_shared/notes-for-all-members.md
docs/pages/_shared/template-audit.md
docs/planning/frontend-template-integration-plan.md
src/Views/layouts/main.php
src/Views/pages/search-content.php
public/assets/css/tokens.css
public/assets/css/base.css
public/assets/css/components.css
public/assets/css/utilities.css
```

## Prompt

> "Make the following specific improvements to search-content.php.
> The backend is already complete — do not change any backend files, routes, controllers, or SQL.
> Do not change variable names. The PHP variables passed to the view are correct.
>
> 1. The `.filter-summary` CSS class does not exist. Replace the active filters block with
>    individual `.tag-pill` spans — one pill per active filter — instead of a plain text string.
>    Example: `<span class="tag-pill">photo_category = formal</span>`. Add a "Clear" link next
>    to the pills that goes to `/search-content` with no query string.
>
> 2. The form button uses `class="form-actions full-span"` but `.full-span` does not exist in
>    components.css. Add this rule to components.css:
>    `.full-span { grid-column: 1 / -1; }`
>
> 3. Add a result count to the Results heading when results are present:
>    `Results (<?= count($results) ?> found)`.
>
> 4. The results table currently shows all CBR columns (photo_category, expression, audio tier,
>    video tier) for every row regardless of file type, with `—` for non-applicable fields.
>    Instead, show only the columns that are relevant to the rows in the current result set.
>    If all results are photos, hide the audio and video columns. If results are mixed, keep
>    all columns but use `.text-dim` on the `—` cells to visually de-emphasise them.
>
> 5. Replace the plain `<p class="muted" style="padding:1rem;">` empty states with a
>    `<div class="card">` containing a short message. Remove all inline `style=` attributes.
>
> Edit only:
> - src/Views/pages/search-content.php
> - public/assets/css/components.css for the `.full-span` rule only
>
> Do not rebuild the page from scratch. Make only the changes listed above."

---

## Verify Before Accepting Codex Output

- `search-content.php` stays a PHP view
- CBR filters match the backend contract — no invented fields
- filter values persist in the form after a search
- empty results state is handled and visible
- shared CSS changes improve consistency without breaking other pages
- no React/Vite/Node code appears
- no backend files are touched
- no route names, variable names, or backend expectations change

---

## What Codex Will Get Wrong Without Context

| Codex default | Must be |
|---|---|
| Over-redesign the whole app | Refine the current template-based shell, do not replace it |
| Add frontend-only fake CBR logic | Backend is complete — do not invent logic in the view |
| Introduce a new CSS framework | Use the existing asset files only |
| Wipe filter form after submit | Filter values must persist across the round-trip |
| Change shared layout and break other pages | Keep changes incremental and safe |

---

## If Codex Generates Something Wrong

Tell it exactly what is wrong. Example:

> "Do not redesign the whole shell or introduce new frameworks. The backend is already complete
> and passes real data into the view. Only refine the CBR page display, ensure filter values
> persist after submit, and keep shared CSS consistent across all pages."

---

## Future Replica Prompt: `/search-content`

If Mahirah needs to rebuild the CBR page from scratch in a new Codex session, use this exact prompt:

> "Build the CBR search page in the existing pure PHP app based on
> `docs/pages/browse/search-content.md` and
> `docs/implementation/system-design/06-retrieval-and-search-contracts.md`.
> Keep it in the current architecture and visual system.
>
> Implement exactly this file:
> - `src/Views/pages/search-content.php`
>
> Exact behavior:
> - GET form with CBR filters: file_type, photo_category, audio_duration_tier, video_resolution_tier
> - Show only the filters that are relevant to the selected file_type
> - Filter values must persist in the form after submit
> - Results table shows relevant metadata columns per file type
> - Empty results state must be visible and not a blank table
>
> Exact shared UI rules:
> - Buttons, cards, forms, and tables must be visually consistent with all other pages
> - Do not redesign the shared shell or navigation
> - Keep CSS changes incremental — only add what is needed for the CBR page
>
> Stay in PHP/CSS only. Do not add pagination, React, new frameworks, or schema changes.
> Do not change routes, backend contracts, variable names, or the shared shell in main.php."
