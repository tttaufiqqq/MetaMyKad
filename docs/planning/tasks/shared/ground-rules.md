# Ground Rules

Status: Draft  
Type: Team rules

## Rules

- Use pure PHP. Do not introduce Laravel, Symfony, or another full-stack framework.
- Keep SQL in reusable repository or query helper files, not mixed deeply into templates.
- Use prepared statements for all database writes and searches.
- Validate uploads by MIME type and size before moving the file.
- Never trust the file extension alone.
- Keep browser-facing pages under `public/` and reusable logic under `src/`.
- Do not hardcode upload paths, DB credentials, or limits inside page files.
- Any destructive action must ask for confirmation first.
- If a file delete fails on disk, log or flag it for cleanup instead of hiding the failure.
- When a behavior changes, update the matching docs file in the same branch if possible.
