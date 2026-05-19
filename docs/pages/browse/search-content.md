# `/search-content.php` - Content Search

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
│  Content-Based Search                                            │
│  ────────────────────                                            │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ FILTERS                                                    │  │
│  │  Photo category   [All ▼]   Expression    [All ▼]          │  │
│  │  Audio tier       [All ▼]   Min bitrate   [______] kbps    │  │
│  │  Video tier       [All ▼]   Duration      [__] to [__] sec │  │
│  │                                            [ Search ]      │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  Results: 9 found                                                │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │  Student        File type   CBR fields                     │  │
│  │  ───────────────────────────────────────────────────────   │  │
│  │  Ahmad Ali      Photo       formal | happy | bg #f5f5f5[V] │  │
│  │  Siti Rahimah   Audio       medium | 192 kbps          [V] │  │
│  │  Razif Hakim    Video       FHD | 134 sec              [V] │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

**Owner:** Mahirah
**Access:** Authenticated

---

## Purpose

Search photo, audio, and video analysis fields stored in `cbr_metadata`.

## Filters

- photo category
- dominant expression
- audio duration tier
- minimum audio bitrate
- video resolution tier
- video duration range

## Results

- student name
- matched file type
- matched CBR fields
- link to detail page
