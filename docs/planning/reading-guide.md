# MetaMyKad - Reading Guide

**Parent:** [Planning docs](./README.md)  
**Related:**
- [Setup](./setup.md)
- [Implementation flow](./implementation-flow.md)
- [Implementation specs](../implementation/system-design/README.md)

---

> Before writing any code, find your name below and read only the files listed.
> Taufiq completes the backend first. The other 4 members should not begin real frontend
> wiring until the backend phases they depend on are complete.

## Taufiq - Backend: Core, Database, Uploads, Retrieval

**Task file:** `docs/planning/tasks/taufiq/task.md`

| File | Why |
|---|---|
| `docs/planning/setup.md` | local PHP, MySQL, and database file setup |
| `docs/architecture/erd.md` | target schema and relationship rules |
| `docs/implementation/system-design/01-pure-php-architecture.md` | app structure and request flow |
| `docs/implementation/system-design/02-database-schema-and-sql-plan.md` | tables, indexes, and SQL constraints |
| `docs/implementation/system-design/03-registration-and-reregistration-flow.md` | transaction-safe create and update flow |
| `docs/implementation/system-design/04-file-upload-and-storage-contract.md` | upload validation and storage rules |
| `docs/implementation/system-design/05-metadata-extraction-and-analysis-rules.md` | IC parsing, PDF extraction, photo/audio/video analysis |
| `docs/implementation/system-design/06-retrieval-and-search-contracts.md` | ABR, TBR, and CBR search contracts |
| `docs/implementation/system-design/07-badge-history-and-audit-rules.md` | badge and history write rules |
| `docs/implementation/system-design/08-delete-and-cleanup-policy.md` | safe delete policy |
| `docs/implementation/system-design/09-testing-and-acceptance-plan.md` | backend verification checklist |
| `docs/planning/tasks/taufiq/task.md` | exact files and phase ownership |
| `docs/planning/tasks/taufiq/claude-code-guide.md` | exactly how to use Claude Code safely for this task |

**Do not wait on frontend.** Your backend work is the dependency that unlocks everyone else.

## Ammar - Frontend: Registration, Re-Registration, Student Detail

**Task file:** `docs/planning/tasks/ammar/task.md`

| File | Why |
|---|---|
| `docs/pages/registration/registration-form.md` | target registration page behavior |
| `docs/pages/registration/re-registration.md` | target re-registration behavior |
| `docs/pages/browse/student-detail.md` | target detail page behavior |
| `docs/pages/_shared/design-system.md` | shared UI language |
| `docs/pages/_shared/notes-for-all-members.md` | shared page rules |
| `docs/planning/tasks/ammar/task.md` | exact scope and wait conditions |
| `docs/planning/tasks/ammar/codex-guide.md` | exactly how to use Codex safely for this task |

**Wait for:** Taufiq Phase 1 for real registration flow and Phase 3 for detail page data wiring.

## Huda - Frontend: Dashboard, History

**Task file:** `docs/planning/tasks/huda/task.md`

| File | Why |
|---|---|
| `docs/pages/browse/dashboard.md` | dashboard target behavior |
| `docs/pages/browse/history.md` | history page target behavior |
| `docs/pages/_shared/design-system.md` | shared UI language |
| `docs/pages/_shared/notes-for-all-members.md` | shared page rules |
| `docs/planning/tasks/huda/task.md` | exact scope and wait conditions |
| `docs/planning/tasks/huda/codex-guide.md` | exactly how to use Codex safely for this task |

**Wait for:** Taufiq Phase 3 because both pages depend on real summary and history data.

## Insyirah - Frontend: ABR, TBR

**Task file:** `docs/planning/tasks/insyirah/task.md`

| File | Why |
|---|---|
| `docs/pages/browse/search-attribute.md` | ABR target behavior |
| `docs/pages/browse/search-text.md` | TBR target behavior |
| `docs/implementation/system-design/06-retrieval-and-search-contracts.md` | search filters and result shape |
| `docs/pages/_shared/design-system.md` | shared UI language |
| `docs/planning/tasks/insyirah/task.md` | exact scope and wait conditions |
| `docs/planning/tasks/insyirah/codex-guide.md` | exactly how to use Codex safely for this task |

**Wait for:** Taufiq Phase 3 because real ABR/TBR results depend on backend search queries.

## Mahirah - Frontend: CBR, Shared Layout And Styling

**Task file:** `docs/planning/tasks/mahirah/task.md`

| File | Why |
|---|---|
| `docs/pages/browse/search-content.md` | CBR target behavior |
| `docs/pages/_shared/design-system.md` | shared visual direction |
| `docs/pages/_shared/notes-for-all-members.md` | shared page rules |
| `docs/planning/tasks/mahirah/task.md` | exact scope and wait conditions |
| `docs/planning/tasks/mahirah/codex-guide.md` | exactly how to use Codex safely for this task |

**Wait for:** Taufiq Phase 3 because CBR filters depend on real `cbr_metadata` search support.

## Shared - All Frontend Members

Read these once before starting any frontend work:

| File | Why |
|---|---|
| `docs/pages/_shared/design-system.md` | colours, typography, spacing, and shared page components |
| `docs/pages/_shared/notes-for-all-members.md` | shared implementation rules |
| `docs/planning/implementation-flow.md` | why frontend waits for backend completion |
| `docs/planning/frontend-template-integration-plan.md` | how the frontend team should reuse the imported template safely |

## Why This Order Exists

The backend owns:

- the database schema
- MySQL functions, views, and procedures
- file storage rules
- metadata extraction
- badge and history writes
- search result contracts

If 4 frontend members wire pages before those are stable, they will build against guessed result
shapes and guessed behavior. That creates rework and merge pain later. Backend first keeps one
source of truth.

## Source Of Truth

When documents overlap, use this order:

1. `docs/implementation/system-design/*` for implementation details
2. `docs/architecture/*` for schema and process intent
3. `docs/product/*` for high-level goals
