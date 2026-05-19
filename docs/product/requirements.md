# MetaMyKad - System Requirements

**Parent:** [Product docs](./README.md)  
**Related:**
- [PRD](./prd.md)
- [ERD](../architecture/erd.md)
- [Testing and acceptance](../implementation/system-design/09-testing-and-acceptance-plan.md)

Status: Draft  
Type: Target behavior

## Functional Requirements

1. The system must accept `ic_number`, `full_name`, `phone`, and `email` for every student.
2. The system must derive `date_of_birth`, `gender`, `state_of_birth`, and `age` from the IC.
3. The system must classify email into `personal`, `student`, or `work`.
4. The system must allow optional uploads for `photo`, `audio`, `pdf`, and `video`.
5. The system must store file metadata including filename, stored filename, file path, file size,
   MIME type, and upload timestamp.
6. The system must extract searchable PDF text when a PDF is uploaded.
7. The system must store CBR metadata for supported photo, audio, and video uploads.
8. The system must allow user-defined tags on files for TBR.
9. The system must support:
   - ABR by structured student and file attributes
   - TBR by extracted PDF text and tags
   - CBR by photo, audio, and video analysis fields
10. The system must compute badge level from uploaded file completeness.
11. The system must support re-registration for an existing IC number.
12. The system must write a registration history row for new and updated submissions.
13. The system must allow staff to delete a file record and trigger physical file cleanup.
14. The system must provide a dashboard showing totals, recent uploads, and badge distribution.

## Non-Functional Requirements

- Implementation must use pure PHP without Laravel, Symfony, or other full-stack frameworks.
- Database must use MySQL with InnoDB and foreign keys.
- Uploaded files must be stored on the server filesystem, not as BLOB columns.
- Search results should remain readable and usable on desktop and mobile browsers.
- Validation errors must be shown clearly on the same page that triggered them.
- SQL queries for search must be parameterized.
- File upload paths and config values must not be hardcoded inside page templates.

## Quality Requirements

- re-registration must avoid partial database writes
- duplicate IC numbers must not create duplicate student rows
- text search must support MySQL FULLTEXT for extracted PDF content
- file type validation must rely on MIME detection, not extension only
- all destructive actions must be confirmed in the UI
