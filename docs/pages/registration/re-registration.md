# `/re-register` - Edit Profile

**Parent:** [Page specs](../README.md)
**Related:**
- [Registration form](./registration-form.md)
- [Login](./login.md)
- [Re-registration flow](../../implementation/system-design/03-registration-and-reregistration-flow.md)
- [Feedback and dialogs](../_shared/feedback-and-dialogs.md)

Status: Draft
Type: Target behavior

**Owner:** Ammar
**Access:** Owner only (must be logged in)

---

## Purpose

Let the logged-in student update their own profile details, replace any of their media files,
change their password, and view their current media submissions inline before replacing them.

## Layout

```
┌──────────────────────────────────────────────────────────────────────┐
│  [Photo]          Name      [________________________]               │
│  [Change Photo]   Matric No [B032420099 — read only  ]               │
│                   Phone     [________________________]               │
│  [← Back to       Group     [GR01 ▼                  ]               │
│    Gallery  ]     Life Motto[________________________]               │
│  [Metadata  ]                                                        │
│   Dashboard ]     Audio Submission                                   │
│  [Logout    ]     [▶ ──────────────────────────── 4:54]              │
│                   Update Audio File  [Choose File]                   │
│                                                                      │
│                   Change Password    [________________________]      │
├──────────────────────────────────────────────────────────────────────┤
│  Document (PDF Viewer)          │  Video Submission                  │
│  [inline PDF render             │  [inline video player]             │
│   with scrollbar]               │                                   │
│  Update Document (.pdf)         │  Update Video (.mp4)               │
│  [Choose File]                  │  [Choose File]                     │
├──────────────────────────────────────────────────────────────────────┤
│                      [ SAVE ALL CHANGES ]                            │
└──────────────────────────────────────────────────────────────────────┘
```

---

## Access Rule

This page is only reachable when a student is logged in. The session `student_id` is used to
load the record — there is no IC lookup field. If the session is missing, redirect to `/login`
with a warning flash.

## Editable Fields

| Field | Type | Notes |
|---|---|---|
| Photo | File upload | Shown as current image top-left; replace via Choose File |
| Name | Text | Pre-filled from DB |
| Phone | Text | Pre-filled from DB |
| Group | Select / Text | Pre-filled from DB (e.g. GR01) |
| Life Motto | Text | Pre-filled from DB; free text |
| Audio | File upload | Current audio shown as inline player |
| PDF | File upload | Current PDF shown as inline viewer |
| Video | File upload | Current video shown as inline player |
| Password | Password | Leave blank to keep existing password |

## Read-Only Fields

| Field | Notes |
|---|---|
| Matric No | Displayed but cannot be changed |

## Sidebar Actions

| Button | Action |
|---|---|
| Change Photo | Triggers the photo file input |
| Back to Gallery | Navigates to `/students` (public student grid) |
| Metadata Dashboard | Navigates to `/dashboard` |
| Logout | `POST /logout` — destroys session, redirects to Home |

## Behavior

- page loads pre-filled with the logged-in student's current data
- current photo is shown top-left; clicking Change Photo opens file picker
- current audio, PDF, and video are shown as inline players before the replace input
- if a file input is left empty, the existing file is kept
- password field: if left blank, existing password is unchanged; if filled, hash and update
- `POST /register` handles the save (same endpoint as new registration, upsert by `student_id`)
- on success: redirect back to `/re-register` with flash `success`

## Important Rules

- matric number is read-only and cannot be changed after registration
- do not create a second student row — this is always an update
- if no new file is chosen for a media type, do not delete the existing file
- password update must use `password_hash()` — never store plain text
