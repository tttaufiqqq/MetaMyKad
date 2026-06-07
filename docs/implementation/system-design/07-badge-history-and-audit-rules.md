# Badge, History, And Audit Rules

**Parent:** [Implementation specs](./README.md)  
**Related:**
- [Registration flow](./03-registration-and-reregistration-flow.md)
- [History page](../../pages/browse/history.md)

Status: Draft  
Type: Implementation target

## Badge Mapping

| Uploaded file count | Badge |
|---|---|
| 0 | `Pendaftar` |
| 1 | `Pelajar` |
| 2 | `Aktif` |
| 3 | `Dedikasi` |
| 4 | `Cemerlang` |

## Badge Rule

Badge is based on the current count of active file types attached to the student.

## History Write Rules

Write one `registration_history` row when:

- a new student is created
- an existing student is re-registered

Record at minimum:

- `ic_number`
- `registered_at`
- `files_uploaded`
- `badge_at_time`
- `action`

## Audit Intent

History is for reconstruction and reporting. It should let staff answer:

- when was this student first registered
- how many files existed at each submission
- which badge level applied at that time
