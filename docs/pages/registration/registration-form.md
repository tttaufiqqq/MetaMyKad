# `/register.php` - Registration Form

**Parent:** [Page specs](../README.md)  
**Related:**
- [Design system](../_shared/design-system.md)
- [Registration flow](../../implementation/system-design/03-registration-and-reregistration-flow.md)

Status: Draft  
Type: Target behavior

## Wireframe — Malaysian Citizen (IC)

```
┌──────────────────────────────────────────────────────────────────┐
│  MetaMyKad                          [Register] [Dashboard]       │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Register New Student                                            │
│  ─────────────────────                                           │
│                                                                  │
│  Student type:  (•) Malaysian Citizen   ( ) International        │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ IDENTITY                                                   │  │
│  │  IC Number  [____________________]  [ Parse ]              │  │
│  │  Full Name  [____________________]                         │  │
│  │  Phone      [____________________]                         │  │
│  │  Email      [____________________]                         │  │
│  │                                                            │  │
│  │  Derived from IC (shown after parse):                      │  │
│  │  ┌──────────┐ ┌────────┐ ┌────────────┐ ┌──────┐          │  │
│  │  │ DOB      │ │ Gender │ │ State      │ │ Age  │          │  │
│  │  │ 01/01/90 │ │ Male   │ │ Selangor   │ │  35  │          │  │
│  │  └──────────┘ └────────┘ └────────────┘ └──────┘          │  │
│  │  Email category: [ Student ]                               │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ UPLOADS (optional)                                         │  │
│  │  ┌────────────┐ ┌────────────┐ ┌──────────┐ ┌──────┐      │  │
│  │  │ [+] Photo  │ │ [+] Audio  │ │ [+] PDF  │ │[+]Vid│      │  │
│  │  └────────────┘ └────────────┘ └──────────┘ └──────┘      │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│                        [ Register Student ]                      │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

## Wireframe — International Student (Passport)

```
┌──────────────────────────────────────────────────────────────────┐
│  MetaMyKad                          [Register] [Dashboard]       │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Register New Student                                            │
│  ─────────────────────                                           │
│                                                                  │
│  Student type:  ( ) Malaysian Citizen   (•) International        │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ IDENTITY                                                   │  │
│  │  Passport No  [____________________]                       │  │
│  │  Full Name    [____________________]                       │  │
│  │  Phone        [____________________]                       │  │
│  │  Email        [____________________]                       │  │
│  │  Date of Birth  [____________________]  (manual entry)     │  │
│  │  Gender         ( ) Male   ( ) Female                      │  │
│  │                                                            │  │
│  │  Email category: [ Personal ]                              │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ UPLOADS (optional)                                         │  │
│  │  ┌────────────┐ ┌────────────┐ ┌──────────┐ ┌──────┐      │  │
│  │  │ [+] Photo  │ │ [+] Audio  │ │ [+] PDF  │ │[+]Vid│      │  │
│  │  └────────────┘ └────────────┘ └──────────┘ └──────┘      │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│                        [ Register Student ]                      │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

**Owner:** Ammar
**Access:** Public

---

## Purpose

Collect one student record and up to four optional media uploads. Supports both Malaysian citizens (identified by IC) and international students (identified by passport).

## Student Type

| Registration with | Student type | Stored as |
|---|---|---|
| Malaysian IC number (12 digits) | Malaysian Citizen | `citizen` |
| Passport number | International Student | `international` |

The student type selector appears at the top of the form and switches the identity section.

## Sections

- student type selector
- identity fields (changes based on type)
- derived metadata preview (citizen only)
- email category preview
- optional uploads
- success summary with assigned badge

## Required Fields — Citizen

- IC number (12 digits, must be unique)
- matric number (must be unique — used as login username)
- password (set once at registration, stored hashed)
- full name
- phone
- email

## Required Fields — International

- passport number (must be unique)
- matric number (must be unique — used as login username)
- password (set once at registration, stored hashed)
- full name
- phone
- email
- date of birth (manual entry — cannot be derived)
- gender (manual entry — cannot be derived)

## Optional Upload Fields

- photo
- audio
- PDF
- video

## Derived Fields — Citizen Only

When a valid IC number is entered, the following are parsed automatically and shown as a preview before submit:

| Derived field | Source |
|---|---|
| `date_of_birth` | first 6 digits of IC (YYMMDD) |
| `gender` | last digit of IC (odd = Male, even = Female) |
| `state_of_birth` | 7th and 8th digit (PB code lookup) |
| `age` | computed from `date_of_birth` |

International students have no IC derivation. `state_of_birth` is left null for international registrations.

## Behavior

- student type toggle switches the identity section without page reload
- for citizen: parse IC on valid input, show derived fields before submit
- for international: show manual DOB and gender fields instead
- email category is derived from the email domain for both types
- show selected filenames and sizes after upload selection
- show success message with assigned badge after submit
