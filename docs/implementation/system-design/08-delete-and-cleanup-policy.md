# Delete And Cleanup Policy

**Parent:** [Implementation specs](./README.md)  
**Related:**
- [Upload contract](./04-file-upload-and-storage-contract.md)
- [Badge rules](./07-badge-history-and-audit-rules.md)

Status: Draft  
Type: Implementation target

## Delete Goal

Remove a file record safely without silently leaving bad database or filesystem state.

## Recommended Delete Sequence

1. Load the target `file_metadata` row.
2. Start a transaction.
3. Delete related `file_tags`.
4. Delete related `cbr_metadata` if present.
5. Delete the `file_metadata` row.
6. Recompute the student's badge.
7. Commit the database transaction.
8. Attempt physical file deletion with `unlink()`.
9. If physical delete fails, log the path for manual cleanup.

## Why File Delete Happens After Commit

MySQL transactions cannot roll back filesystem changes. Deleting the file after commit avoids the
worse failure mode where the file is gone but the database row remains.

## UI Rule

- require explicit confirmation
- show whether DB delete succeeded and whether disk cleanup also succeeded
