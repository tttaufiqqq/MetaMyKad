# Template Audit

**Parent:** [Page specs](../README.md)  
**Related:**
- [Shared design system](./design-system.md)
- [Frontend template integration plan](../../planning/frontend-template-integration-plan.md)
- [Template source folder](../../../template/)

Status: Draft  
Type: Reuse audit

## Decision

Use the `template/` folder as a **visual and interaction reference**, not as the live frontend
stack.

## Why Not Reuse The Whole Template As-Is

The template is a Vite + React + TypeScript app with AI Studio / Gemini-oriented project files.
MetaMyKad is a pure PHP + MySQL project that already uses:

- `public/index.php`
- `src/Views/`
- `public/assets/`
- PHP routing and server-rendered pages

Directly adopting the template stack would create a second architecture inside the repo and
conflict with the backend-first group plan.

## Exact Parts Worth Reusing

### 1. `template/style.css`

**Reuse:** Yes, heavily  
**Why:** This file contains the strongest design work in the template.

Reusable ideas:

- dark teal / cyan visual direction
- sidebar + header + dashboard shell
- card, table, and form styling
- upload box styling
- metadata card styling
- footer status bar style
- subtle animation and background grid atmosphere

### 2. `template/index.html`

**Reuse:** Yes, structurally  
**Why:** The page grouping and dashboard shell map well to MetaMyKad.

Reusable ideas:

- sidebar navigation grouping
- dashboard overview section
- registration split layout
- search and history layout concepts

### 3. `template/script.js`

**Reuse:** Partial only  
**Why:** It contains useful prototype behaviors, but not production-ready source-of-truth logic.

Reusable ideas:

- UI behavior patterns for navigation states
- visual feedback ideas for uploads
- badge display concept
- IC parsing and email classification as a UX preview concept only

Do **not** treat its logic as backend truth. IC parsing, email classification, metadata extraction,
and badge rules must stay backend-driven.

## Exact Parts Not Worth Reusing

### 1. `template/package.json`

**Reuse:** No  
**Why:** It introduces React, Vite, Express, and Gemini dependencies that do not belong in the
current pure PHP app.

### 2. `template/src/App.tsx`, `template/src/main.tsx`, `template/src/index.css`

**Reuse:** No as application code  
**Why:** These files belong to the React stack, and `App.tsx` is effectively empty anyway.

### 3. `template/vite.config.ts`, `template/tsconfig.json`

**Reuse:** No  
**Why:** Build tooling does not match the PHP architecture.

### 4. `template/.env.example`

**Reuse:** No  
**Why:** It is oriented toward the template runtime, not MetaMyKad's PHP/MySQL config.

## What Was Ported Into The PHP App

The current PHP frontend now reuses these template qualities:

- dark teal / cyan dashboard palette
- sidebar navigation shell
- header badge display area
- card and table treatment
- upload tile presentation
- footer status bar
- grid background effect
- subtle entrance motion and hover states

Ported files include:

- `public/assets/css/tokens.css`
- `public/assets/css/base.css`
- `public/assets/css/components.css`
- `public/assets/css/utilities.css`
- `src/Views/layouts/main.php`

## Rule For The Frontend Team

Reuse the template's **look and component ideas**.
Do not reintroduce React, Vite, or template-specific runtime assumptions into the PHP project.
