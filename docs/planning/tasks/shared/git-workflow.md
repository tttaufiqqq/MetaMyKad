# Git Workflow

Status: Draft  
Type: Team rules

## Branching

- `main` stays stable
- create short feature branches such as `feature/registration-flow`
- one branch should focus on one work area

## Commits

- keep commits small and reviewable
- use clear messages, for example `Add IC parsing helper` or `Implement PDF text search`
- avoid mixing schema changes and unrelated UI polish in one commit

## Before Merge

- run the local verification steps from the docs
- confirm schema changes are reflected in SQL docs
- confirm new pages match the page spec that owns them
