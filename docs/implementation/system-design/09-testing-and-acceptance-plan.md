# Testing And Acceptance Plan

**Parent:** [Implementation specs](./README.md)  
**Related:**
- [Requirements](../../product/requirements.md)
- [Page specs](../../pages/README.md)

Status: Draft  
Type: Verification guide

## Core Acceptance Checks

- valid IC input derives the correct date of birth, gender, and state
- duplicate IC reuses the existing student update flow
- each supported file type is accepted only when MIME and size rules pass
- PDF upload stores searchable extracted text
- ABR, TBR, and CBR each return at least one seeded result
- badge changes after upload replacement or delete
- registration history grows on every create or update

## Manual Test Matrix

| Area | Minimum test |
|---|---|
| registration | submit with no files and with all files |
| re-registration | replace one file and keep the others |
| photo metadata | verify photo CBR row is written |
| PDF text search | search for a known PDF keyword |
| dashboard | totals and badge distribution render |
| delete | remove one file and verify badge recompute |

## Failure Tests

- invalid IC format
- unsupported MIME type
- PDF extraction failure
- missing upload folder write permission
- deleting a file whose disk path no longer exists
