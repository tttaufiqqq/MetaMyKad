# Task Directory

## Step 1 — Find Your Name

| Member | Task file | Codex guide | What you own |
|---|---|---|---|
| Taufiq | [task.md](./taufiq/task.md) | [Claude Code guide](./taufiq/claude-code-guide.md) | All backend work |
| Ammar | [task.md](./ammar/task.md) | [Codex guide](./ammar/codex-guide.md) | Registration, re-registration, student detail UI |
| Huda | [task.md](./huda/task.md) | [Codex guide](./huda/codex-guide.md) | Dashboard and history UI |
| Insyirah | [task.md](./insyirah/task.md) | [Codex guide](./insyirah/codex-guide.md) | ABR and TBR search UI |
| Mahirah | [task.md](./mahirah/task.md) | [Codex guide](./mahirah/codex-guide.md) | CBR search UI and shared styling |

## Step 2 — Read Your Task File

Open your `task.md`. It tells you:

- **Role** — which pages you own
- **Current State** — what is already built vs what is a stub
- **What To Do Now** — exactly what to work on, in order
- **Files You Will Mainly Touch** — so you know what not to break
- **Done Means** — the acceptance criteria for your work

## Step 3 — Use Your Codex Guide

Open your `codex-guide.md`. It gives you:

- The exact files to paste into Codex before starting
- A ready-made implementation prompt to paste into Codex
- A checklist to verify Codex output before accepting it
- Common mistakes Codex makes and how to correct them

## Step 4 — After You Finish

```bash
# stage only your own files
git add src/Views/pages/your-file.php public/assets/css/components.css

# commit with a clear message
git commit -m "feat: build registration form view"

# push to your branch
git push
```

Do not commit files outside your task scope without checking with Taufiq first.

---

## Shared References

- [Ground rules](./shared/ground-rules.md) — coding and commit conventions
- [Git workflow](./shared/git-workflow.md) — branch, commit, and merge guidance
- [Who to ask when stuck](./shared/who-to-ask-when-stuck.md) — ownership map by area

## Before Starting For The First Time

Make sure the app runs locally before opening Codex.

- [Local dev setup](../setup.md) — PHP server, MySQL, `.env`, SQL files
- [Implementation specs](../../implementation/system-design/README.md) — how the backend works
