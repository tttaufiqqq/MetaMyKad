# Shared Design System

Status: Draft  
Type: Target behavior

## Visual Direction

Use the imported `template/` visual language, but adapt it to the pure PHP app.

- dark teal / cyan technology dashboard mood
- strong table readability
- clear compact form labels
- compact metadata cards
- high contrast for validation and result states
- subtle motion, not noisy motion

## Layout Rules

- app-level sidebar stays consistent across all pages
- top header carries the current page title and badge display
- public registration pages should use the template's split layout
- dashboard and search pages should feel like one monitoring console
- search filters must stay visible above result tables

## Components

- summary cards for metrics
- grouped form sections with compact labels
- upload tiles for multimedia entry points
- metadata cards for file previews
- searchable tables with compact uppercase headers
- confirmation modal for destructive actions

## Source Reference

See [template-audit.md](./template-audit.md) for the exact parts of the template that are safe to
reuse and the parts that should remain only as reference.
