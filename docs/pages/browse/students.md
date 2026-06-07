# `/students.php` - All Students

**Parent:** [Page specs](../README.md)
**Related:**
- [Dashboard](./dashboard.md)
- [Student detail](./student-detail.md)
- [Design system](../_shared/design-system.md)

Status: Draft
Type: Target behavior
**Owner:** Huda
**Access:** Authenticated

---

## Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  MetaMyKad                          [Register] [Dashboard]       │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  All Students (42)                                               │
│  ─────────────────                                               │
│                                                                  │
│  Search [____________________]  Type [All ▼]  Badge [All ▼]     │
│                                                                  │
│  ┌───────────────┐ ┌───────────────┐ ┌───────────────┐          │
│  │   ╭───────╮   │ │   ╭───────╮   │ │   ╭───────╮   │  ...    │
│  │   │ photo │   │ │   │ photo │   │ │   │ photo │   │          │
│  │   ╰───────╯   │ │   ╰───────╯   │ │   ╰───────╯   │          │
│  │  Ahmad Ali    │ │  Siti Rahimah │ │  Britney Ng   │          │
│  │  [Citizen]    │ │  [Citizen]    │ │  [Intl]       │          │
│  │  Dedikasi     │ │  Pelajar      │ │  Aktif        │          │
│  └───────────────┘ └───────────────┘ └───────────────┘          │
│                                                                  │
│  ┌───────────────┐ ┌───────────────┐ ┌───────────────┐          │
│  │   ╭───────╮   │ │   ╭───────╮   │ │   ╭───────╮   │  ...    │
│  │   │ photo │   │ │   │ photo │   │ │   │ photo │   │          │
│  │   ╰───────╯   │ │   ╰───────╯   │ │   ╰───────╯   │          │
│  │  Razif Hakim  │ │  Nurul Farah  │ │  Huda Najihah │          │
│  │  [Citizen]    │ │  [Citizen]    │ │  [Citizen]    │          │
│  │  Cemerlang    │ │  Aktif        │ │  Pelajar      │          │
│  └───────────────┘ └───────────────┘ └───────────────┘          │
│                                                                  │
│  (no photo uploaded → show placeholder avatar)                  │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## Purpose

Show every registered student as a photo card grid. Gives a visual overview of the registry and a fast entry point to individual records.

## Layout

- grid of cards, minimum 4 per row on desktop, 2 per row on mobile
- each card shows:
  - circular photo thumbnail (placeholder avatar if no photo uploaded)
  - full name
  - student type badge: `Citizen` or `International`
  - current badge level
- clicking any card navigates to `/student-detail.php?id={id}`

## Filters

- name search (live filter or on submit)
- student type: All / Citizen / International
- badge level: All / Pendaftar / Pelajar / Aktif / Dedikasi / Cemerlang

## Student Type Badge

| Value | Shown as | Meaning |
|---|---|---|
| `citizen` | `Citizen` | registered using Malaysian IC number |
| `international` | `Intl` | registered using passport number |

## Behavior

- total count shown in page heading
- empty state when no students are registered yet — suggest the registration link
- filter combination is additive (name AND type AND badge)
- no pagination required for the initial build — scroll through all results

## Backend Data Shape

```php
// StudentModel::getAllWithPhoto()
// returns rows from students JOIN file_metadata (photo only, latest upload)
[
  'id'           => 1,
  'full_name'    => 'Ahmad bin Ali',
  'student_type' => 'citizen',   // 'citizen' | 'international'
  'badge'        => 'Dedikasi',
  'photo_path'   => 'uploads/photo/1234_photo.jpg',  // null if none
]
```
