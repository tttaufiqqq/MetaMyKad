# Frontend Template Integration Plan

**Parent:** [Planning docs](./README.md)  
**Related:**
- [Template audit](../pages/_shared/template-audit.md)
- [Implementation flow](./implementation-flow.md)
- [Task directory](./tasks/README.md)

Status: Draft  
Type: Frontend rollout plan

## Goal

Use the `template/` folder as a design reference while keeping MetaMyKad inside the pure PHP
project structure.

## Reuse Principle

Frontend members should reuse:

- the template visual language
- card and table layouts
- upload tile treatment
- spacing and dashboard composition

Frontend members should not reuse:

- React app structure
- Vite build tooling
- Gemini/AI Studio dependencies
- template JavaScript as backend truth

## Current Shared Port

The shared shell already ported into the PHP app includes:

- sidebar navigation
- dark teal header and status footer
- updated token palette
- card, form, table, and upload-box styles
- basic motion and hover effects

This means the frontend team should build **on top of the current PHP structure**, not start from
the raw `template/` folder.

## Member Assignment Plan

### Ammar - Registration, Re-Registration, Student Detail

Own these files:

- `src/Views/pages/register.php`
- `src/Views/pages/re-register.php`
- `src/Views/pages/student-detail.php`

Template patterns to reuse:

- registration two-column composition from `template/index.html`
- upload tile styling from `template/style.css`
- extraction feedback box style from `template/script.js` concept

Main responsibility:

- make registration flows feel polished and clear
- keep upload presentation consistent
- prepare detail-page metadata cards

### Huda - Dashboard, History

Own these files:

- `src/Views/pages/dashboard.php`
- `src/Views/pages/history.php`

Template patterns to reuse:

- dashboard metric cards
- metadata dashboard cards
- table container style
- footer/system-status atmosphere

Main responsibility:

- make dashboard metrics easy to scan
- keep history table readable and consistent with the shell

### Insyirah - ABR, TBR

Own these files:

- `src/Views/pages/search-attribute.php`
- `src/Views/pages/search-text.php`

Template patterns to reuse:

- filter panel grouping from search section
- search result table styling
- consistent control spacing

Main responsibility:

- make ABR and TBR search feel precise and understandable
- keep filter and result states visually aligned

### Mahirah - CBR, Shared UI Consistency

Own these files:

- `src/Views/pages/search-content.php`
- shared CSS refinements in `public/assets/css/`
- shared partial polish when needed

Template patterns to reuse:

- CBR filter grouping
- metadata-rich result presentation
- hover and card consistency across the app

Main responsibility:

- make CBR results visually distinct but still consistent
- keep shared component styling unified across all pages

## Dependency Rule

Frontend members may polish layout early, but real data integration should wait until Taufiq's
backend phases are complete for the relevant pages.

## Acceptance Criteria

- all page work stays inside the PHP frontend structure
- the UI clearly reflects the imported template style
- no member introduces React/Vite code into the live app
- page layouts remain ready for Taufiq's real backend data
