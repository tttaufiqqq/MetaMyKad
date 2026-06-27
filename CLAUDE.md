\## Rule 1 — Think Before Coding

State assumptions explicitly. Ask rather than guess.

Push back when a simpler approach exists. Stop when confused.



\## Rule 2 — Simplicity First

Minimum code that solves the problem. Nothing speculative.

No abstractions for single-use code.



\## Rule 3 — Surgical Changes

Touch only what you must. Don't improve adjacent code.

Match existing style. Don't refactor what isn't broken.



\## Rule 4 — Goal-Driven Execution

Define success criteria. Loop until verified.

Strong success criteria let Claude loop independently.



\## Rule 5 — Use the Model Only for Judgment Calls

Use for: classification, drafting, summarization, extraction.

Do NOT use for: routing, retries, deterministic transforms.

If code can answer, code answers.



\## Rule 6 — Token Budgets Are Not Advisory

Per-task: 4,000 tokens. Per-session: 30,000 tokens.

If approaching budget, summarize and start fresh.

Surface the breach. Do not silently overrun.



\## Rule 7 — Surface Conflicts, Don't Average Them

If two patterns contradict, pick one (more recent / more tested).

Explain why. Flag the other for cleanup.



\## Rule 8 — Read Before You Write

Before adding code, read exports, immediate callers, shared utilities.

If unsure why existing code is structured a certain way, ask.



\## Rule 9 — Tests Verify Intent, Not Just Behavior

Tests must encode WHY behavior matters, not just WHAT it does.

A test that can't fail when business logic changes is wrong.



\## Rule 10 — Checkpoint After Every Significant Step

Summarize what was done, what's verified, what's left.

Don't continue from a state you can't describe back.



\## Rule 11 — Match the Codebase's Conventions, Even If You Disagree

Conformance > taste inside the codebase.

If you think a convention is harmful, surface it. Don't fork silently.



\## Rule 12 — Fail Loud

"Completed" is wrong if anything was skipped silently.

"Tests pass" is wrong if any were skipped.

Default to surfacing uncertainty, not hiding it.



\## Rule 13 — Commit messages are documentation

When committing, always write:

1\. A summary line: `type(scope): what changed and why` (≤72 chars)

2\. A body with at minimum: what the problem was, what was tried, what

&#x20;  the solution is, and which files changed meaningfully.

Never commit with a one-line message only. The commit history is the

project's decision log.

Dont add co author by claude in the commit.



\## Rule 14 — File Length Limit

No file may exceed 200 lines.

If a file would exceed 200 lines, split it into focused part files before writing.

Each part file covers one concern only (e.g. one component, one page, one feature).
