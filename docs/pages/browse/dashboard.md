# `/dashboard.php` - Dashboard

**Parent:** [Page specs](../README.md)  
**Related:**
- [Student detail](./student-detail.md)
- [History](./history.md)

Status: Draft  
Type: Target behavior

## Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  MetaMyKad                          [Register] [Dashboard]       │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Dashboard                                                       │
│  ─────────                                                       │
│                                                                  │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌────────┐   │
│  │ TOTAL        │ │ TOTAL FILES  │ │ PHOTOS       │ │ PDFs   │   │
│  │ STUDENTS     │ │              │ │              │ │        │   │
│  │      42      │ │     138      │ │      39      │ │   27   │   │
│  └──────────────┘ └──────────────┘ └──────────────┘ └────────┘   │
│                                                                  │
│  ┌──────────────────────────────┐ ┌─────────────────────────┐    │
│  │ RECENT REGISTRATIONS         │ │ BADGE DISTRIBUTION      │    │
│  │ ───────────────────────────  │ │ ──────────────────────  │    │
│  │ Ahmad   860101-..  Aktif     │ │ Pendaftar  ████    12   │    │
│  │ Siti    950303-..  Pelajar   │ │ Pelajar    ██████  18   │    │
│  │ Razif   001201-..  Cemerlg   │ │ Aktif      ████     8   │    │
│  │ Nurul   890505-..  Aktif     │ │ Dedikasi   ██       3   │    │
│  │ [View all →]                 │ │ Cemerlang  █        1   │    │
│  └────────────────────────────  ┘ └─────────────────────────┘    │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ QUICK LINKS                                                │  │
│  │  [All Students]  [ABR]  [TBR]  [CBR]  [History]           │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

**Owner:** Huda
**Access:** Authenticated

---

## Purpose

Give a quick overview of the registry and a starting point into search and record review.

## Recommended Widgets

- total students (with citizen / international split)
- total files by type
- recent registrations
- badge distribution
- quick links to all students, ABR, TBR, CBR, and history pages

## Behavior

- total students widget shows the full count, with citizen count and international count below it
- recent rows link to the student detail page
- "View all" in the recent registrations panel links to `/students.php`
- metrics are derived from real DB counts
- empty state suggests the registration page when no data exists yet
