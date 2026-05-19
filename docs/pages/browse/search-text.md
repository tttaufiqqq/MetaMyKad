# `/search-text.php` - Text Search

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
│  Text-Based Search                                               │
│  ─────────────────                                               │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ SEARCH                                                     │  │
│  │  Keyword      [________________________________]           │  │
│  │  Exact phrase [ ] match exact phrase                       │  │
│  │  Tag filter   [____________________]  [ Search ]           │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  Results: 6 found                                                │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │  Student         File   Match source    Snippet            │  │
│  │  ─────────────────────────────────────────────────────── │  │
│  │  Ahmad Ali       PDF    extracted text  "...algorithm      │  │
│  │                                          is used to..."[V] │  │
│  │  Siti Rahimah    PDF    extracted text  "...the algo       │  │
│  │                                          produces..."  [V] │  │
│  │  Razif Hakim     all    tag: algorithm                 [V] │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

**Owner:** Insyirah
**Access:** Authenticated

---

## Purpose

Search PDF extracted text and file tags.

## Inputs

- keyword
- exact phrase
- optional tag

## Results

- matched student
- snippet of matched text when available
- matched tag if the result came through tagging
- link to detail page
