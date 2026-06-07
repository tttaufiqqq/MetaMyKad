# `/search-attribute.php` - Attribute Search

**Parent:** [Page specs](../README.md)  
**Related:**
- [Search contracts](../../implementation/system-design/06-retrieval-and-search-contracts.md)
- [Student detail](./student-detail.md)

Status: Draft  
Type: Target behavior

## Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  MetaMyKad                          [Register] [Dashboard]       │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Attribute-Based Search                                          │
│  ──────────────────────                                          │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ FILTERS                                                    │  │
│  │  Name          [____________________]                      │  │
│  │  Gender        [All ▼]  State        [All ▼]               │  │
│  │  Email cat.    [All ▼]  Badge        [All ▼]               │  │
│  │  File type     [All ▼]  MIME type    [All ▼]               │  │
│  │                                      [ Search ]            │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  Results: 14 found                                               │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │  Name            IC             Badge      Files   State   │  │
│  │  ───────────────────────────────────────────────────────   │  │
│  │  Ahmad Ali       860101-14-5x   Dedikasi   3/4     KL [V]  │  │
│  │  Siti Rahimah    950303-10-6x   Pelajar    1/4     Sel [V] │  │
│  │  Razif Hakim     001201-14-7x   Cemerlang  4/4     Png [V] │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

**Owner:** Insyirah
**Access:** Authenticated

---

## Purpose

Search structured metadata using filters from `students` and `file_metadata`.

## Filters

- name
- gender
- state of birth
- email category
- badge
- file type
- MIME type

## Results

- table or cards with student name
- matched file info when file filters are active
- link to detail page
