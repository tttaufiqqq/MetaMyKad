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

- [ ] Read src/Controllers/RegistrationController.php in full
- [ ] Read src/Views/pages/register.php in full
- [ ] Read src/Views/pages/re-register.php in full
- [ ] Map what is already implemented vs stub

### Phase 5b: Registration Form View (pages/register.php)

- [ ] Add form action="/register" method="POST" enctype="multipart/form-data"
- [ ] Add CSRF hidden input using the csrf partial
- [ ] Add text input: ic_number (required, pattern 12 digits)
- [ ] Add text input: matric_number (required)
- [ ] Add password input: password (required)
- [ ] Add text input: full_name (required)
- [ ] Add text input: phone
- [ ] Add email input: email (required)
- [ ] Add file input: photo (accept image/jpeg, image/png)
- [ ] Add file input: audio (accept audio/mpeg, audio/wav)
- [ ] Add file input: pdf (accept application/pdf)
- [ ] Add file input: video (accept video/mp4, video/quicktime, video/x-msvideo)
- [ ] Display flash error messages from session (use toast partial)
- [ ] Display per-field validation errors if re-rendering the form

### Phase 5c: RegistrationController@store — Validation

- [ ] Validate ic_number: required, exactly 12 digits, numeric only
- [ ] Validate matric_number: required, not empty
- [ ] Validate password: required, minimum 8 characters
- [ ] Validate full_name: required, not empty
- [ ] Validate email: required, valid email format
- [ ] On any validation failure: re-render register.php with errors (do not redirect)

### Phase 5d: RegistrationController@store — IC and Email Parsing

- [ ] Call Student::deriveFromIc($ic_number) — get dob, gender, state_of_birth, age
- [ ] If deriveFromIc returns null or throws: flash error "IC number is invalid", stop
- [ ] Call Student::classifyEmail($email) — get email_category
- [ ] Hash password using password_hash($password, PASSWORD_DEFAULT)

### Phase 5e: RegistrationController@store — New Registration DB Write

- [ ] Check if ic_number already exists in students table (Student::findByIc)
- [ ] If new student: call PDO to CALL sp_RegisterStudent with all derived params
- [ ] Retrieve the newly inserted student_id (LAST_INSERT_ID() or findByIc after insert)
- [ ] Update students.password with the hashed password (sp_RegisterStudent does not handle password)
- [ ] Proceed to upload pipeline (Phase 6), then call sp_UpdateBadge(student_id)
- [ ] Flash "Registration successful" and redirect to /dashboard

### Phase 5f: RegistrationController@store — Re-Registration Path

- [ ] If ic_number exists: switch to re-registration flow
- [ ] Load all existing file_metadata rows for that student
- [ ] For each existing stored_filename: attempt unlink(file_path)
- [ ] If any unlink() returns false: flash "Could not remove old files, registration aborted", stop
- [ ] Call sp_RegisterStudent (procedure handles history snapshot, DELETE old rows, UPDATE students)
- [ ] Retrieve student_id from students WHERE ic_number = p_ic
- [ ] Re-run upload pipeline for new files (Phase 6)
- [ ] Call sp_UpdateBadge(student_id)
- [ ] Flash "Re-registration successful" and redirect to /student-detail?id={student_id}

### Phase 5g: Re-Registration Lookup View (pages/re-register.php)

- [ ] Add a search form (GET) with a single ic_number field to look up an existing student
- [ ] If ic_number provided via GET: query students table, display found student's name and current badge
- [ ] Show a confirmation message: "This will replace all existing files for this student"
- [ ] Reuse the full registration form pre-filled with found student data (name, phone, email)
- [ ] Display flash messages

### Phase 5h: Integration Tests

- [ ] Submit a new registration with all 4 files — verify students row, 4 file_metadata rows, badge='Cemerlang', history row action='new'
- [ ] Submit a new registration with only 2 files — verify badge='Aktif'
- [ ] Submit a duplicate IC — verify re-registration path is taken, old files unlinked, history row action='update'
- [ ] Submit with invalid IC (11 digits) — verify error, no DB write
- [ ] Submit with missing required fields — verify validation errors rendered on form

## Progress Log

