# Registration And Re-Registration Flow

**Parent:** [Implementation specs](./README.md)  
**Related:**
- [Flow of events](../../architecture/flow-of-events.md)
- [Badge rules](./07-badge-history-and-audit-rules.md)

Status: Draft  
Type: Implementation target

## New Registration

1. Validate required text inputs.
2. Parse IC number and derive demographic fields.
3. Classify email category.
4. Start a database transaction.
5. Insert the `students` row.
6. Process each uploaded file and insert related metadata rows.
7. Compute badge from uploaded file count.
8. Update the student badge if needed.
9. Insert one `registration_history` row with action `new`.
10. Commit.

## Re-Registration

Physical files are unlinked via PHP `unlink()` before the transaction begins. If unlinking
fails, the transaction does not proceed.

Transaction sequence:

1. Snapshot existing state to `registration_history` (action `update`).
2. Delete old `cbr_metadata` rows linked to this student's files.
3. Delete old `file_tags` rows linked to this student's files.
4. Delete old `file_metadata` rows for this student.
5. Update `students` core data (name, phone, email, email_category, updated_at).
6. Insert new file records and their CBR/tag rows.
7. Recompute and update badge.
8. Commit.

If any step fails, `ROLLBACK` is called.

## Important Rules

- Re-registration is an update flow, not a second student insert flow.
- All old files are deleted and re-inserted in full — not partially replaced.
- `unlink()` happens before the transaction; a failed unlink aborts the whole operation.

## Failure Handling

- if IC parsing fails, stop before the transaction
- if a file move or metadata insert fails, roll back the transaction
- if one optional file fails, the whole submission should fail unless the team explicitly designs
  a partial-success mode later
