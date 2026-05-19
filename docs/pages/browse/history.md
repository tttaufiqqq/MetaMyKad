# `/history.php` - Registration History

**Parent:** [Page specs](../README.md)  
**Related:**
- [Student detail](./student-detail.md)
- [Badge and history rules](../../implementation/system-design/07-badge-history-and-audit-rules.md)

Status: Draft  
Type: Target behavior

## Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  MetaMyKad                          [Register] [Dashboard]       │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Registration History                                            │
│  ────────────────────                                            │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ FILTER                                                     │  │
│  │  IC Number  [______________________]  [ Filter ]           │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │  IC Number      Timestamp             Action  Files  Badge │  │
│  │  ───────────────────────────────────────────────────────   │  │
│  │  860101-14-5x   2025-05-19 10:32:01   update  3     Ded.   │  │
│  │  860101-14-5x   2025-03-10 08:14:22   new     1     Pel.   │  │
│  │  950303-10-6x   2025-05-18 14:07:55   new     0     Pend.  │  │
│  │                                                            │  │
│  │  [← Detail page for 860101-14-5x]                          │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

**Owner:** Huda
**Access:** Authenticated

---

## Purpose

Inspect historical registration events over time.

## Columns

- IC number
- registration timestamp
- action
- files uploaded at the time
- badge at the time

## Behavior

- support filter by IC number
- support newest-first ordering
- allow navigation back to the related detail page when the student still exists
