# Feedback And Dialogs

**Parent:** [Page specs](../README.md)
**Related:**
- [Design system](./design-system.md)
- [Notes for all members](./notes-for-all-members.md)
- [Delete and cleanup policy](../../implementation/system-design/08-delete-and-cleanup-policy.md)

Status: Draft
Type: Implementation target

---

## Rule: Never Use Browser Dialogs

Do not call `window.alert()`, `window.confirm()`, or `window.prompt()` anywhere.
All feedback must use the custom components described in this file.

---

## 1. Flash Toast — Cross-Page Messages

Use after any POST action that redirects. The flash is set in PHP before the redirect and
displayed once on the next page load.

### PHP — Set a Flash (in a Controller)

```php
// Success
$this->flash('success', 'Registration saved successfully.');

// Warning
$this->flash('warning', 'File could not be deleted from disk. Record removed.');

// Error
$this->flash('error', 'Registration failed. Please try again.');
```

Outside a controller, use the class directly:

```php
use MetaMyKad\Core\Flash;

Flash::set('success', 'Student registered.');
```

### Types and When to Use Each

| Type | CSS class | When to use |
|---|---|---|
| `success` | `.toast-success` | Action completed fully — registration saved, file deleted |
| `warning` | `.toast-warning` | Partial success or soft failure — DB delete succeeded but disk cleanup failed |
| `error` | `.toast-error` | Action failed entirely — transaction rolled back, validation rejected |

### How the Layout Renders It

`main.php` calls `Flash::get()` which reads and clears `$_SESSION['_flash']` in one call.
The result is passed to `toast.php`:

```php
// src/Views/layouts/main.php (already wired)
$flash = Flash::get();
// then inside the body:
include __DIR__ . '/../partials/toast.php';
```

`toast.php` renders only when `$flash` is a non-null array:

```html
<!-- rendered output example -->
<div class="toast toast-success" data-toast>
    <span>Registration saved successfully.</span>
    <button type="button" class="toast-close" data-toast-close aria-label="Close notification">x</button>
</div>
```

The close button (`data-toast-close`) is wired in `app.js` — clicking it removes the element.

### Rules

- One flash per request cycle. Do not call `Flash::set()` more than once before a redirect.
- The flash auto-clears on read — it will not appear again on refresh.
- Never render the toast inline inside a page view. Always redirect after POST, then let the
  layout pick it up.

---

## 2. Inline Field Errors — Same-Page Validation

Use when a form fails validation and you re-render the same page. Do not redirect on validation
failure — the user must see the errors next to the fields.

### PHP — Pass Errors to the View

Store old input and errors in session before re-rendering:

```php
$_SESSION['_errors'] = [
    'ic_number' => 'IC number must be exactly 12 digits.',
    'email'     => 'Email address is not valid.',
];
$_SESSION['_old'] = $_POST;
redirect('/register');
```

In the view, read them:

```php
$errors = $_SESSION['_errors'] ?? [];
$old    = $_SESSION['_old'] ?? [];
unset($_SESSION['_errors'], $_SESSION['_old']);
```

### HTML — Render Field Error

Place the error span immediately after the input it belongs to:

```html
<div class="form-group">
    <label for="ic_number">IC Number</label>
    <input type="text"
           id="ic_number"
           name="ic_number"
           value="<?= e($old['ic_number'] ?? '') ?>"
           class="<?= isset($errors['ic_number']) ? 'input-error' : '' ?>">
    <?php if (isset($errors['ic_number'])): ?>
        <span class="field-error"><?= e($errors['ic_number']) ?></span>
    <?php endif; ?>
</div>
```

### CSS — Add These Classes to `components.css`

These classes do not exist yet and must be added:

```css
.field-error {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.72rem;
    color: rgba(239, 68, 68, 0.9);
}

.input-error {
    border-color: rgba(239, 68, 68, 0.55);
    background: var(--color-danger-bg);
}
```

### Rules

- Show field errors inline, not as a flash toast.
- Preserve input values using `$_SESSION['_old']` so the user does not retype the form.
- Clear `_errors` and `_old` from session after reading them in the view.
- A summary flash toast (`error`, e.g. "Please fix the errors below.") may be shown alongside
  inline field errors, but inline errors are the primary indicator.

---

## 3. Confirmation Dialog — Destructive Actions

Use before any delete or irreversible action. The dialog is a custom modal rendered once in the
layout. Never use `window.confirm()`.

### How to Trigger It

Add `data-confirm="your message"` to any `<button>` or `<a>` element. `app.js` intercepts the
click, injects the message into the dialog, and waits for the user to accept or cancel.

#### On a Form Button (file delete)

```html
<form method="POST" action="/delete-file">
    <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
    <input type="hidden" name="file_id" value="<?= e($file['id']) ?>">
    <button type="submit"
            class="button danger"
            data-confirm="Delete this file? This cannot be undone.">
        Delete
    </button>
</form>
```

#### On a Link (navigation-triggered action)

```html
<a href="/delete-student?ic=<?= e($student['ic_number']) ?>"
   data-confirm="Delete this student and all their files?">
    Delete Registration
</a>
```

### How It Works

`app.js` listens for all clicks on `[data-confirm]` elements:

1. Reads the `data-confirm` attribute and writes it to `#confirm-dialog-message`.
2. Records the pending form or href.
3. Removes the `hidden` class from `#confirm-dialog` to show the overlay.
4. Cancel button: clears pending state, re-adds `hidden`.
5. Accept button: submits `pendingForm` or navigates `pendingHref`.

The dialog is included once inside `main.php` via:

```php
include __DIR__ . '/../partials/confirm-dialog.php';
```

### HTML Structure (already in `confirm-dialog.php`)

```html
<div class="confirm-dialog hidden" id="confirm-dialog" aria-hidden="true">
    <div class="confirm-dialog__panel">
        <h3>Confirm action</h3>
        <p id="confirm-dialog-message">Are you sure you want to continue?</p>
        <div class="confirm-dialog__actions">
            <button type="button" class="button secondary" data-confirm-cancel>Cancel</button>
            <button type="button" class="button" data-confirm-accept>Confirm</button>
        </div>
    </div>
</div>
```

### Rules

- Every delete button must have `data-confirm`. No exceptions.
- Write the message in plain language describing what will be lost.
- The confirm button label stays "Confirm". Do not rename it to "Delete" — the message is
  already clear from the dialog body.
- Never wire a delete action to a GET request that executes immediately on click.

---

## 4. Decision Guide — Which Component to Use

| Situation | Use |
|---|---|
| POST succeeded, redirecting away | Flash toast (`success`) |
| POST partially succeeded (e.g. DB ok, disk failed) | Flash toast (`warning`) |
| POST failed entirely (DB rollback, server error) | Flash toast (`error`) |
| Form validation failed, re-rendering same page | Inline field errors + optional summary toast |
| User about to delete a file | Confirm dialog |
| User about to delete a full student registration | Confirm dialog |
| Auth check failed, redirecting to login | Flash toast (`warning`) |

---

## 5. Component File Locations

| File | Purpose |
|---|---|
| `src/Core/Flash.php` | `Flash::set()` and `Flash::get()` — session-based flash store |
| `src/Controllers/BaseController.php` | `$this->flash($type, $message)` shorthand |
| `src/Views/partials/toast.php` | Renders the flash toast HTML |
| `src/Views/partials/confirm-dialog.php` | Renders the confirm modal HTML |
| `src/Views/layouts/main.php` | Calls `Flash::get()` and includes both partials |
| `public/assets/js/app.js` | Wires `[data-confirm]`, `[data-toast-close]` |
| `public/assets/css/components.css` | `.toast-*`, `.confirm-dialog*` styles |
| `public/assets/css/tokens.css` | `--color-success-bg`, `--color-warning-bg`, `--color-danger-bg` |
