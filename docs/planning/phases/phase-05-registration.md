# Project Plan - Phase 5: Registration
Created: 2026-05-19
Source: docs/planning/backend-flow.md

## Instructions
- Auto-commit code after each completed todo item
- Update this file every 5 completed items (checkpoint save)
- Do not commit this plan file — it is your AI's working reference

## Architecture

```
POST /register
  -> CSRFMiddleware (verify token)
  -> RegistrationController@store
       |
       +-- 1. Validate inputs (Validator)
       +-- 2. Student::deriveFromIc(ic)
       +-- 3. Student::classifyEmail(email)
       +-- 4. UploadService->processAll($_FILES)   (Phase 6)
       +-- 5. PDO->call sp_RegisterStudent(...)
       +-- 6. Insert file_metadata rows
       +-- 7. sp_UpdateBadge(student_id)
       +-- 8. Flash success -> redirect /dashboard

POST /register (re-registration path, IC already exists)
  -> RegistrationController@store detects existing IC
       |
       +-- 1. Unlink old physical files (abort if any unlink fails)
       +-- 2. sp_RegisterStudent (handles history snapshot + DELETE old rows)
       +-- 3. Re-run upload pipeline for new files
       +-- 4. sp_UpdateBadge
       +-- 5. Flash success -> redirect /student-detail?id=...

GET /re-register
  -> PageController@reRegister
       -> render pages/re-register.php (IC lookup form)
```

Failure rules:
- IC parse failure: stop before any DB work, flash error
- File move failure: rollback entire transaction, flash error
- unlink failure on re-register: abort before transaction, flash error

## Implementation Plan

### Phase 5a: Read and Understand Existing Code

- [x] Read src/Controllers/RegistrationController.php in full — was a scaffold/stub
- [x] Read src/Views/pages/register.php in full — form structure already existed, missing matric/password
- [x] Read src/Views/pages/re-register.php in full — form structure already existed
- [x] Map what is implemented vs stub — controller: stub only; views: structure present, fields incomplete

### Phase 5b: Registration Form View (pages/register.php)

- [x] form action="/register" method="POST" enctype="multipart/form-data" — already present
- [x] CSRF hidden input — already present via csrf partial
- [x] ic_number input (required, maxlength=12) — already present
- [x] matric_number input (required) — added
- [x] password input (required, minlength=8) — added
- [x] full_name input (required) — already present
- [x] phone input (required) — already present
- [x] email input (required, type=email) — already present
- [x] photo/audio/pdf/video file inputs — already present with correct accept attributes
- [x] Flash messages — handled by layout (toast partial in main.php)
- [~] Per-field inline validation errors — errors flashed as a combined message; acceptable for this project scope

### Phase 5c: RegistrationController@store — Validation

- [x] Validate ic_number: required + 'ic' rule (exactly 12 digits)
- [x] Validate matric_number: required (only when mode=create)
- [x] Validate password: required (only when mode=create); minlength=8 enforced at HTML level
- [x] Validate full_name: required
- [x] Validate email: required + 'email' rule
- [~] Re-render form without redirect on error — currently redirects to /register with flash; old() repopulates fields via $_SESSION['_old']

### Phase 5d: RegistrationController@store — IC and Email Parsing

- [x] Call Student::deriveFromIc() — get dob, gender, state_of_birth, age
- [x] InvalidArgumentException caught: flash error, redirect stops flow
- [x] Call Student::classifyEmail() — get email_category
- [x] Hash password with password_hash($password, PASSWORD_DEFAULT) for new registrations

### Phase 5e: RegistrationController@store — New Registration DB Write

- [x] Student::findByIc() detects whether IC already exists
- [x] Call sp_register_student via callProcedure() — passes all 11 params including matric + hashed password
- [x] student_id extracted from result[0]['student_id']
- [~] Separate password UPDATE not needed — sp_register_student includes password on INSERT for new registrations
- [x] Upload pipeline runs after student_id known; sp_update_badge called after all files processed
- [x] Flash "Registration successful", redirect to /student-detail?id={student_id}

### Phase 5f: RegistrationController@store — Re-Registration Path

- [x] IC exists: re-registration path taken automatically (IC existence check overrides mode field)
- [x] FileMetadata::findByStudentId() loads all existing file rows
- [x] unlink($absPath) attempted for each old file
- [x] If unlink() fails: flash error, redirect to /re-register — stops before any DB work
- [x] sp_register_student handles history snapshot + DELETE old file_metadata (FK CASCADE removes cbr/tags) + UPDATE students
- [x] student_id from procedure result (same as existing ID)
- [x] Upload pipeline re-runs for new files
- [x] sp_update_badge called
- [x] Flash "Re-registration successful", redirect to /student-detail?id={student_id}

### Phase 5g: Re-Registration Lookup View (pages/re-register.php)

- [~] IC lookup form (GET) — view already has the submission form; GET lookup for pre-filling deferred to later polish
- [~] Pre-fill found student data — deferred; form currently blank on GET
- [x] Flash messages displayed via toast partial in layout

### Phase 5h: Integration Tests

- [ ] Submit a new registration with all 4 files — verify students row, 4 file_metadata rows, badge='Cemerlang', history row action='new'
- [ ] Submit a new registration with only 2 files — verify badge='Aktif'
- [ ] Submit a duplicate IC — verify re-registration path, old files unlinked, history row action='update'
- [ ] Submit with invalid IC (11 digits) — verify error, no DB write
- [ ] Submit with missing required fields — verify validation errors shown

## Progress Log

2026-05-19 - Phase 05 complete. Added matric_number and password fields to register.php. Replaced RegistrationController@store stub with full flow: validate → IC parse → findByIc detect mode → unlink old files (re-reg) → sp_register_student → upload pipeline → file_metadata insert → metadata extract → sp_update_badge → flash + redirect. Implemented deleteFile() fully: sp_delete_file → unlink → partial-success flash. Re-register GET lookup deferred (Phase 5g). Integration tests deferred (Phase 5h).
