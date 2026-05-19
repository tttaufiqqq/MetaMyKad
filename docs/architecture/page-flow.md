# Page Flow

**Parent:** [Architecture docs](./README.md)
**Related:**
- [Flow of events](./flow-of-events.md)
- [Use case and activity](./use-case-activity.md)
- [Page specs](../pages/README.md)

---

## Full System Page Flow

```
                               ┌──────────────┐
                               │     Home     │
                               └──────┬───────┘
                                      │
              ┌───────────────┬────────┴──────────┬───────────────┐
              │               │                   │               │
              ▼               ▼                   ▼               ▼
    ┌──────────────┐  ┌──────────────┐   ┌──────────────┐  ┌──────────────┐
    │   Register   │  │    Login     │   │  Dashboard   │  │   History    │
    └──────┬───────┘  └──────┬───────┘   └──────┬───────┘  └──────────────┘
           │                 │                   │
           │ POST /register  │ POST /login        ├──────────────────────────┐
           │                 │ (session created)  │                          │
           ▼                 ▼                   ▼                          ▼
    ┌──────────────┐  ┌──────────────┐   ┌───────────────────┐  ┌──────────────────┐
    │     Home     │  │     Home     │   │ Search Attribute  │  │   Search Text    │
    │(flash success│  │(flash success│   └─────────┬─────────┘  └────────┬─────────┘
    │  + log in)   │  │)             │             │                     │
    └──────────────┘  └──────────────┘             └──────────┬──────────┘
                                                              │
                                                   ┌──────────┴──────────┐
                                                   │   Search Content    │
                                                   └──────────┬──────────┘
                                                              │
                                                              │ row click
                                                              ▼
                                                   ┌─────────────────────┐
                                                   │   Student Detail    │
                                                   └──────────┬──────────┘
                                                              │
                            ┌─────────────────────────────────┤
                            │ owner logged in                 │ owner logged in
                            ▼                                 ▼
                 ┌──────────────────────┐        ┌──────────────────────┐
                 │  Edit Profile        │        │  POST /delete-file   │
                 │  (Re-register)       │        └──────────┬───────────┘
                            │                               │
                            │ POST /register                │
                            ▼                               ▼
                 ┌──────────────────────┐        ┌──────────────────────┐
                 │   Student Detail     │        │   Student Detail     │
                 │   (flash success)    │        │   (flash result)     │
                 └──────────────────────┘        └──────────────────────┘
```

---

## Auth Rules

```
Public (no login needed)
    ├── Home                /
    ├── Register            /register
    ├── Login               /login
    ├── Dashboard           /dashboard
    ├── Search Attribute    /search-attribute
    ├── Search Text         /search-text
    ├── Search Content      /search-content
    ├── Student Detail      /student-detail
    └── History             /history

Owner only (must be logged in + own the record)
    ├── Re-register         /re-register
    ├── Delete file         POST /delete-file
    └── Logout              POST /logout
```

---

## Error Pages

```
Any route
    ├── not owner / not logged in  ──► 403
    ├── not found                  ──► 404
    └── server error               ──► 500
```

---

## Page Reference

| Page | Route | Auth |
|---|---|---|
| Home | `/` | Public |
| Register | `/register` | Public |
| Login | `/login` | Public |
| Dashboard | `/dashboard` | Public |
| Search Attribute | `/search-attribute` | Public |
| Search Text | `/search-text` | Public |
| Search Content | `/search-content` | Public |
| Student Detail | `/student-detail` | Public |
| History | `/history` | Public |
| Re-register | `/re-register` | Owner only |
| Delete file | `POST /delete-file` | Owner only |
| Logout | `POST /logout` | Owner only |
| 403 | — | System |
| 404 | — | System |
| 500 | — | System |
