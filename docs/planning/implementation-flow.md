# MetaMyKad - Implementation Flow

**Parent:** [Planning docs](./README.md)  
**Related:**
- [Setup](./setup.md)
- [Task directory](./tasks/README.md)
- [Build order](../implementation/system-design/10-implementation-sequence-and-build-order.md)

---

## Core Principle

**Backend contract first - frontend wiring second. Never the reverse.**

MetaMyKad is a database-heavy project. The frontend pages depend directly on:

- table names
- view names
- stored procedure names
- search result columns
- badge and history behavior

If those are not stable first, the frontend team will guess and later rewrite their pages.

---

## Phase 0 - Taufiq: Backend Project Foundation

**Who:** Taufiq  
**When:** First. Before frontend members do real integration work.  
**Prompt:** `docs/planning/tasks/taufiq-backend-core-database-and-retrieval.md`

### What gets built

- final pure PHP project structure
- `public/index.php` front controller flow
- `src/Core/` bootstrap, router, DB connection, session, flash, CSRF, validator
- `database/schema.sql`, `database/functions.sql`, `database/views.sql`, `database/procedures.sql`

### Why this is first

Every later registration, search, and dashboard behavior depends on these foundations.
Without stable SQL objects and a stable app structure, the team builds against moving targets.

### Before moving on

- project runs through `public/index.php`
- all database files exist in `database/`
- app can connect to MySQL locally

---

## Phase 1 - Taufiq: Registration And Re-Registration Backend

**Who:** Taufiq  
**Depends on:** Phase 0  
**Prompt:** `docs/planning/tasks/taufiq-backend-core-database-and-retrieval.md` - Phase 1

### What gets built

- IC parsing logic
- email category derivation
- create student flow
- re-registration update flow
- registration history procedure usage
- badge recomputation after create/update

### Why before frontend registration wiring

The registration page is not just a form. Its success behavior depends on derived metadata,
badge logic, and history writes. The frontend should not guess any of that.

### Before moving on

- new registration writes correct student row
- re-registration updates the same student row by `ic_number`
- history rows are written for `new` and `update`

---

## Phase 2 - Taufiq: Upload, Delete, And Metadata Backend

**Who:** Taufiq  
**Depends on:** Phase 1  
**Prompt:** `docs/planning/tasks/taufiq-backend-core-database-and-retrieval.md` - Phase 2

### What gets built

- file upload validation
- filesystem storage rules
- `file_metadata` insert flow
- `cbr_metadata` insert flow
- PDF text extraction
- file delete DB flow through stored procedure
- badge recomputation after file change

### Why before student detail and CBR work

Student detail, delete actions, and CBR screens all depend on these records being real.

### Before moving on

- one student can upload photo, audio, PDF, and video successfully
- extracted PDF text is stored
- delete flow updates DB state and recalculates badge

---

## Phase 3 - Taufiq: Search And Dashboard Backend

**Who:** Taufiq  
**Depends on:** Phase 2  
**Prompt:** `docs/planning/tasks/taufiq-backend-core-database-and-retrieval.md` - Phase 3

### What gets built

- ABR query support
- TBR query support
- CBR query support
- dashboard summary queries
- history retrieval queries
- student detail query shape

### Why this phase unlocks the frontend team

This is the point where result shapes stop being guesses. After this phase, the frontend members
can wire their pages against real backend output safely.

### Before moving on

- ABR returns filtered student results
- TBR returns keyword or tag matches
- CBR returns media-analysis matches
- dashboard metrics read from real SQL
- history page data can be fetched reliably

---

## Phase 4 - Frontend Team: Shared UI Shell

**Who:** Mahirah + all frontend members  
**Depends on:** Phase 0 complete  
**Prompt:** each member task file

### What gets built

- shared layout polishing
- navbar consistency
- CSS token and component cleanup
- shared page presentation rules

### Why this can start early

Visual shell work can happen before data integration as long as the team does not invent backend
behavior or hardcode fake contracts.

---

## Phase 5 - Frontend Team: Page Wiring After Backend

**Who:** Ammar + Huda + Insyirah + Mahirah  
**Depends on:** Phases 1 to 3 complete  
**Prompts:** each member task file

### Ammar - Registration, Re-Registration, Student Detail

- wire public form pages to the real backend behavior
- render derived feedback correctly
- render student detail sections around real backend data

### Huda - Dashboard, History

- wire dashboard metrics and recent activity
- wire registration history table and filters

### Insyirah - ABR, TBR

- wire attribute search form and results
- wire text search form and results

### Mahirah - CBR, Shared UI Integration

- wire content search form and results
- keep shared styles consistent across all pages

### Before moving on

- each frontend page consumes real backend-backed data
- no page depends on guessed SQL shape
- delete confirmations and success/error feedback are consistent

---

## Summary Table

| Phase | Who | What | Depends on |
|---|---|---|---|
| 0 | Taufiq | project foundation, app core, database files | Nothing |
| 1 | Taufiq | registration and re-registration backend | Phase 0 |
| 2 | Taufiq | upload, delete, metadata backend | Phase 1 |
| 3 | Taufiq | search and dashboard backend | Phase 2 |
| 4 | Mahirah + frontend team | shared UI shell and styling | Phase 0 |
| 5 | Ammar + Huda + Insyirah + Mahirah | real frontend page wiring | Phase 3 |

---

## Why Not Start Real Frontend Work Immediately?

Because this project is not just forms and tables. It depends heavily on:

- MySQL functions
- MySQL views
- stored procedures
- derived metadata fields
- transaction-driven update rules

If the frontend team starts integration too early, they will wire to guessed shapes and later
rewrite their work. Backend first is faster overall, not slower.
