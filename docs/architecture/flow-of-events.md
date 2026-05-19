# MetaMyKad - Flow Of Events

**Parent:** [Architecture docs](./README.md)  
**Related:**
- [Use case and activity](./use-case-activity.md)
- [Registration flow implementation](../implementation/system-design/03-registration-and-reregistration-flow.md)

Status: Draft  
Type: Target behavior

## 1. New Registration

1. Student opens the registration page.
2. Student enters IC number, name, phone, and email.
3. System derives date of birth, gender, state of birth, and age from the IC.
4. System classifies the email category.
5. Student optionally uploads photo, audio, PDF, and video files.
6. System validates files, stores them, extracts metadata, and computes the badge.
7. System inserts student, file, CBR, tag, and history data.
8. System shows a success summary and the assigned badge.

## 2. Re-Registration

1. Student provides an existing IC number.
2. System loads the current student data and existing files.
3. Student updates form values and can replace selected uploads.
4. System writes a history snapshot, updates the student row, replaces chosen files,
   and recomputes the badge.
5. System confirms which files were kept and which were replaced.

## 3. Attribute-Based Retrieval

1. Staff opens the ABR page.
2. Staff filters by attributes such as gender, state, email category, file type, or badge.
3. System builds a SQL query against `students` and `file_metadata`.
4. Results are shown with links to the student detail page.

## 4. Text-Based Retrieval

1. Staff enters a keyword or phrase.
2. System searches PDF extracted text and optional file tags.
3. Matching files are ranked and shown with student context.

## 5. Content-Based Retrieval

1. Staff selects photo, audio, or video feature filters.
2. System queries `cbr_metadata`.
3. Results are grouped by student and file.

## 6. Safe Delete

1. Staff opens a student detail page.
2. Staff chooses a file to delete.
3. System confirms the action.
4. System removes the file metadata row and records cleanup outcome.
5. System recomputes the student's badge.
