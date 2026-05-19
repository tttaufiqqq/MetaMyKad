# `/login` - Login

**Parent:** [Page specs](../README.md)
**Related:**
- [Registration form](./registration-form.md)
- [Re-registration](./re-registration.md)
- [Feedback and dialogs](../_shared/feedback-and-dialogs.md)

Status: Draft
Type: Target behavior

**Access:** Public

---

## Purpose

Authenticate a registered student so they can edit or delete their own record. Login uses
matric number and the password they set during registration.

## Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  MetaMyKad                          [Register] [Dashboard]       │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Student Login                                                   │
│  ─────────────                                                   │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │  Matric Number  [____________________]                     │  │
│  │  Password       [____________________]                     │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│                          [ Log In ]                              │
│                                                                  │
│  Don't have an account?  → Register                              │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

## Behavior

- `POST /login` validates matric number and password against the `students` table
- on success: store `student_id` and `matric_number` in session, redirect to Home with
  flash `success`
- on failure: re-render login page with flash `error` — do not reveal whether the matric
  number exists or just the password was wrong

## PHP Auth Check

```php
// LoginController@store
$student = $db->query(
    'SELECT id, password FROM students WHERE matric_number = ?',
    [$_POST['matric_number']]
)->fetch();

if (!$student || !password_verify($_POST['password'], $student['password'])) {
    Flash::set('error', 'Invalid matric number or password.');
    redirect('/login');
}

$_SESSION['student_id']     = $student['id'];
$_SESSION['matric_number']  = $_POST['matric_number'];
Flash::set('success', 'Logged in successfully.');
redirect('/');
```

## Logout

`POST /logout` clears the session and redirects to Home.

```php
// LogoutController@store
session_destroy();
redirect('/');
```

## Ownership Check (for Re-register and Delete)

Before allowing edit or delete, verify the logged-in student owns the record:

```php
if (($_SESSION['student_id'] ?? null) !== $targetStudentId) {
    http_response_code(403);
    require view_path('errors/403.php');
    exit;
}
```

## Rules

- never store plain-text passwords — use `password_hash($_POST['password'], PASSWORD_DEFAULT)`
  on registration and `password_verify()` on login
- generic error message on failure — do not say "matric number not found" vs "wrong password"
- session must be started before any session read or write (`session_start()` in bootstrap)
- logout must use `POST` not `GET` to prevent accidental logouts via prefetch
