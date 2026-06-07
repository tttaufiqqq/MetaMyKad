# File Upload And Storage Contract

**Parent:** [Implementation specs](./README.md)  
**Related:**
- [Extraction rules](./05-metadata-extraction-and-analysis-rules.md)
- [Delete policy](./08-delete-and-cleanup-policy.md)

Status: Draft  
Type: Implementation target

## Supported File Types

| Logical type | Examples |
|---|---|
| `photo` | `image/jpeg`, `image/png` |
| `audio` | `audio/mpeg`, `audio/wav`, `audio/x-wav` |
| `pdf` | `application/pdf` |
| `video` | `video/mp4`, `video/quicktime`, `video/x-msvideo` |

## Validation Rules

- inspect MIME with `finfo`
- reject zero-byte files
- enforce per-file size limits in config
- normalize original filenames before storage
- store uploads under folders matching `file_type`

## Naming Rule

Use a generated filename such as:

```text
{student_id}_{file_type}_{timestamp}_{random}.{ext}
```

Store:

- original upload name in `filename`
- generated name in `stored_filename`
- relative path in `file_path`

## Storage Rule

```text
storage/uploads/photo/
storage/uploads/audio/
storage/uploads/pdf/
storage/uploads/video/
```

## Insert Contract

Each accepted upload creates one `file_metadata` row and, when relevant, one `cbr_metadata` row.

## Security Notes

- never trust `$_FILES['type']` alone
- never serve uploads from a writable folder without considering direct access rules
- restrict uploads to the expected MIME list
