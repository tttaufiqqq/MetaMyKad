# `/student-detail.php` - Student Detail

**Parent:** [Page specs](../README.md)  
**Related:**
- [Dashboard](./dashboard.md)
- [Delete policy](../../implementation/system-design/08-delete-and-cleanup-policy.md)

Status: Draft  
Type: Target behavior

## Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  MetaMyKad                          [Register] [Dashboard]       │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ← Back to Dashboard      Student Detail                        │
│  ────────────────────────────────────────                       │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ IDENTITY                              Badge: Dedikasi      │  │
│  │  Name:   Ahmad bin Ali                                     │  │
│  │  IC:     860101-14-5xxx                                    │  │
│  │  Phone:  012-3456789                                       │  │
│  │  Email:  ahmad@student.utm.my                              │  │
│  │  DOB:    01 Jan 1986  Gender: M  State: Kuala Lumpur       │  │
│  │  Age:    39           Email type: Student                  │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ UPLOADED FILES                                             │  │
│  │  [Photo] photo_abc.jpg   2.1 MB  image/jpeg      [Delete] │  │
│  │  [Audio] voice.mp3       0.8 MB  audio/mpeg      [Delete] │  │
│  │  [PDF]   resume.pdf      0.5 MB  application/pdf [Delete] │  │
│  │  [Video] (not uploaded)                                    │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ PDF TEXT PREVIEW                                           │  │
│  │  "Experienced software engineer with expertise in..."      │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ CBR METADATA                                               │  │
│  │  Photo: formal | expression: happy (0.91) | bg: #f5f5f5   │  │
│  │  Audio: 42 sec | medium | 128 kbps                         │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  [View Registration History for this IC]                        │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

**Owner:** Ammar
**Access:** Authenticated

---

## Purpose

Show one student's identity data, derived metadata, uploads, tags, and CBR details.

## Sections

- identity summary
- derived IC and email attributes
- current badge
- file list by type
- extracted PDF text preview
- CBR metadata cards
- delete actions

## Actions

- delete file
- navigate to history page filtered to this IC
