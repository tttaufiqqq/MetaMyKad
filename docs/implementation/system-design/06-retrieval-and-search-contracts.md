# Retrieval And Search Contracts

**Parent:** [Implementation specs](./README.md)  
**Related:**
- [Requirements](../../product/requirements.md)
- [Page specs](../../pages/README.md)

Status: Draft  
Type: Implementation target

## ABR Inputs

- student name
- gender
- state of birth
- email category
- badge
- file type
- MIME type

## TBR Inputs

- keyword
- exact phrase
- optional tag

## CBR Inputs

- photo category
- dominant expression
- audio duration tier
- audio bitrate minimum
- video resolution tier
- video duration range

## Result Shape

Each search result row should include:

- student name
- IC number or masked IC display
- matched file type
- key matched metadata
- link to student detail

## SQL Guidance

- ABR: `students` joined to `file_metadata` only when file filters are active
- TBR: `MATCH(file_metadata.extracted_text) AGAINST (...)` plus optional tag join
- CBR: `cbr_metadata` joined to `file_metadata` and `students`

## Empty State Rule

Every search page must show the active filters and a clear "no matches" state instead of a blank
table.
