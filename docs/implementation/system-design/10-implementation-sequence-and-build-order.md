# Implementation Sequence And Build Order

**Parent:** [Implementation specs](./README.md)  
**Related:**
- [Implementation flow](../../planning/implementation-flow.md)
- [Architecture](../../architecture/README.md)

Status: Draft  
Type: Implementation rollout

## High-Level Sequence

```text
project skeleton
   ->
database schema
   ->
registration flow
   ->
upload pipeline
   ->
metadata extraction
   ->
search pages
   ->
dashboard and detail pages
   ->
delete and cleanup
```

## Phase 1 - Skeleton

- config loading
- DB connection helper
- shared layout includes
- writable storage folders

## Phase 2 - Schema

- create all tables
- add constraints and FULLTEXT
- seed a few test records if needed

## Phase 3 - Registration

- build new registration page
- add IC parsing and email classification
- write student and history rows

## Phase 4 - Media Pipeline

- upload validation
- storage path creation
- metadata and CBR insertion

## Phase 5 - Retrieval

- ABR page
- TBR page
- CBR page
- student detail page

## Phase 6 - Admin Safety

- re-registration
- delete flow
- badge recomputation
- cleanup logging

## Rule

Do not build search pages against guessed columns. Finish the schema and upload pipeline first.
