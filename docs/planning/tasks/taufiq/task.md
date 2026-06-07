# Taufiq - Backend: Core, Database, And Retrieval

**Parent:** [Task directory](../README.md)
**Related:**
- [Reading guide](../../reading-guide.md)
- [Implementation flow](../../implementation-flow.md)
- [ERD](../../../architecture/erd.md)
- [Implementation specs](../../../implementation/system-design/README.md)

Status: Draft  
Type: Ownership guide

## Role

You own the entire backend for MetaMyKad.

That includes:

- PHP app core
- database schema
- MySQL functions, views, and procedures
- registration and re-registration logic
- upload and delete behavior
- metadata extraction pipeline
- ABR, TBR, and CBR backend query support
- dashboard and history data queries

## Why One Backend Owner

This project is database-heavy and highly coupled. The backend is one dependency chain:

```text
schema
  ->
functions
  ->
views
  ->
procedures
  ->
PHP models/controllers
  ->
frontend wiring
```

If multiple people edit this chain at once, naming drift and contract drift will slow the team
down. One backend owner keeps the contract stable for the 4 frontend members.

## Phase 0 - Foundation

Files and folders you own first:

- `public/index.php`
- `src/Core/`
- `src/Models/`
- `config/`
- `database/schema.sql`
- `database/functions.sql`
- `database/views.sql`
- `database/procedures.sql`

Done means:

- app bootstraps cleanly
- DB connection works
- database files can be run in order

## Phase 1 - Registration Backend

Build:

- IC parsing
- email category derivation
- student create flow
- re-registration update flow
- registration history write
- badge recomputation

Done means:

- no duplicate student row for same `ic_number`
- `registration_history` writes for both `new` and `update`

## Phase 2 - Upload And Metadata Backend

Build:

- file upload validation
- filesystem storage paths
- `file_metadata` insert flow
- `cbr_metadata` insert flow
- PDF text extraction
- delete procedure-backed DB cleanup

Done means:

- all 4 file types can be processed
- file delete updates badge correctly

## Phase 3 - Retrieval And Dashboard Backend

Build:

- ABR filtering backend
- TBR keyword and tag backend
- CBR media-analysis backend
- dashboard counts and summaries
- history retrieval queries
- student detail query shape

Done means:

- frontend can wire to real search and dashboard data without guessing

## Important Rule

Do not wait for the frontend team to start backend-critical work. Their work depends on your
contracts, not the other way around.
