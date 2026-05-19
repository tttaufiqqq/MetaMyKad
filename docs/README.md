# MetaMyKad - Docs Index

> Start here. This folder mirrors the `agroguard/docs` structure, but the content is tailored
> to MetaMyKad and a pure PHP implementation.

---

## Stage 1 - Read First

| # | File | What it is |
|---|---|---|
| 1 | [docs/planning/reading-guide.md](./planning/reading-guide.md) | Role-based reading order for the team |
| 2 | [docs/planning/setup.md](./planning/setup.md) | Local setup for plain PHP, MySQL, and media tools |

---

## Stage 2 - Understand The Project

| # | File | What it is |
|---|---|---|
| 3 | [docs/product/prd.md](./product/prd.md) | Product requirements document |
| 4 | [docs/product/requirements.md](./product/requirements.md) | Functional and non-functional requirements |
| 5 | [docs/architecture/erd.md](./architecture/erd.md) | Target database schema and relationship rules |
| 6 | [docs/architecture/flow-of-events.md](./architecture/flow-of-events.md) | End-to-end process flows |
| 7 | [docs/architecture/use-case-activity.md](./architecture/use-case-activity.md) | Use cases and activity summaries |
| 8 | [docs/architecture/page-flow.md](./architecture/page-flow.md) | Full page navigation diagram |

---

## Stage 3 - Planning And Ground Rules

| # | File | What it is |
|---|---|---|
| 8 | [docs/planning/implementation-flow.md](./planning/implementation-flow.md) | Recommended build order |
| 9 | [docs/planning/tasks/shared/ground-rules.md](./planning/tasks/shared/ground-rules.md) | Coding and documentation rules |
| 10 | [docs/planning/tasks/shared/git-workflow.md](./planning/tasks/shared/git-workflow.md) | Branch, commit, and merge guidance |
| 11 | [docs/planning/tasks/shared/who-to-ask-when-stuck.md](./planning/tasks/shared/who-to-ask-when-stuck.md) | Ownership map by work area |

---

## Stage 4 - Role Task Files

| # | File | Who should read it |
|---|---|---|
| 12 | [docs/planning/tasks/taufiq/task.md](./planning/tasks/taufiq/task.md) | Taufiq - all backend work |
| 13 | [docs/planning/tasks/ammar/task.md](./planning/tasks/ammar/task.md) | Ammar - registration and detail UI |
| 14 | [docs/planning/tasks/huda/task.md](./planning/tasks/huda/task.md) | Huda - dashboard and history UI |
| 15 | [docs/planning/tasks/insyirah/task.md](./planning/tasks/insyirah/task.md) | Insyirah - ABR and TBR UI |
| 16 | [docs/planning/tasks/mahirah/task.md](./planning/tasks/mahirah/task.md) | Mahirah - CBR and shared UI |

---

## Stage 5 - Implementation Specs

| # | File | What it is |
|---|---|---|
| 17 | [docs/implementation/system-design/01-pure-php-architecture.md](./implementation/system-design/01-pure-php-architecture.md) | Target folder structure and application shape |
| 18 | [docs/implementation/system-design/02-database-schema-and-sql-plan.md](./implementation/system-design/02-database-schema-and-sql-plan.md) | SQL design, indexes, and DDL notes |
| 19 | [docs/implementation/system-design/03-registration-and-reregistration-flow.md](./implementation/system-design/03-registration-and-reregistration-flow.md) | Transaction flow for create and update |
| 20 | [docs/implementation/system-design/04-file-upload-and-storage-contract.md](./implementation/system-design/04-file-upload-and-storage-contract.md) | Upload validation and file storage rules |
| 21 | [docs/implementation/system-design/05-metadata-extraction-and-analysis-rules.md](./implementation/system-design/05-metadata-extraction-and-analysis-rules.md) | IC parsing, metadata extraction, and CBR rules |
| 22 | [docs/implementation/system-design/06-retrieval-and-search-contracts.md](./implementation/system-design/06-retrieval-and-search-contracts.md) | ABR, TBR, and CBR query contracts |
| 23 | [docs/implementation/system-design/07-badge-history-and-audit-rules.md](./implementation/system-design/07-badge-history-and-audit-rules.md) | Badge logic and audit policy |
| 24 | [docs/implementation/system-design/08-delete-and-cleanup-policy.md](./implementation/system-design/08-delete-and-cleanup-policy.md) | Safe delete and orphan cleanup rules |
| 25 | [docs/implementation/system-design/09-testing-and-acceptance-plan.md](./implementation/system-design/09-testing-and-acceptance-plan.md) | Verification checklist |
| 26 | [docs/implementation/system-design/10-implementation-sequence-and-build-order.md](./implementation/system-design/10-implementation-sequence-and-build-order.md) | Practical rollout sequence |
| 27 | [docs/implementation/system-design/11-stored-procedures-functions-views.md](./implementation/system-design/11-stored-procedures-functions-views.md) | Stored procedures, functions, and views with full SQL |

---

## Stage 6 - Page Specs

**registration/** — entry and identity pages

| # | File | What it is |
|---|---|---|
| 27 | [docs/pages/registration/registration-form.md](./pages/registration/registration-form.md) | Student registration form (public) |
| 28 | [docs/pages/registration/login.md](./pages/registration/login.md) | Login, logout, and ownership check |
| 29 | [docs/pages/registration/re-registration.md](./pages/registration/re-registration.md) | Edit profile — owner only |

**browse/** — view and search pages (all public)

| # | File | What it is |
|---|---|---|
| 30 | [docs/pages/browse/dashboard.md](./pages/browse/dashboard.md) | Dashboard and metrics |
| 31 | [docs/pages/browse/students.md](./pages/browse/students.md) | All students gallery |
| 32 | [docs/pages/browse/student-detail.md](./pages/browse/student-detail.md) | Student record detail view |
| 33 | [docs/pages/browse/search-attribute.md](./pages/browse/search-attribute.md) | Attribute-based retrieval (ABR) |
| 34 | [docs/pages/browse/search-text.md](./pages/browse/search-text.md) | Text-based retrieval (TBR) |
| 35 | [docs/pages/browse/search-content.md](./pages/browse/search-content.md) | Content-based retrieval (CBR) |
| 36 | [docs/pages/browse/history.md](./pages/browse/history.md) | Registration history |

**_shared/** — cross-page guidance

| # | File | What it is |
|---|---|---|
| 37 | [docs/pages/_shared/design-system.md](./pages/_shared/design-system.md) | Shared UI direction |
| 38 | [docs/pages/_shared/notes-for-all-members.md](./pages/_shared/notes-for-all-members.md) | Shared implementation notes |
| 39 | [docs/pages/_shared/feedback-and-dialogs.md](./pages/_shared/feedback-and-dialogs.md) | Flash toasts, field errors, and confirmation dialogs |

---

## Source Inputs

These docs were derived from the current project notes:

- [`docs/product/proposal.md`](./product/proposal.md)
- [`docs/architecture/erd.md`](./architecture/erd.md)
