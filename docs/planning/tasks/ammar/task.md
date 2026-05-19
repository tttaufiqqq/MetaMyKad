# Ammar - Frontend: Registration And Detail Pages

**Parent:** [Task directory](../README.md)
**Related:**
- [Reading guide](../../reading-guide.md)
- [Registration form page spec](../../../pages/registration/registration-form.md)
- [Re-registration page spec](../../../pages/registration/re-registration.md)
- [Student detail page spec](../../../pages/browse/student-detail.md)

Status: Draft  
Type: Ownership guide

## Role

You own the frontend pages for:

- registration
- re-registration
- student detail

## Wait Rule

Do not do real backend integration until:

- Taufiq Phase 1 is complete for registration and re-registration
- Taufiq Phase 3 is complete for student detail data

Before that, you may:

- improve page layout
- improve form grouping
- improve validation presentation
- prepare non-destructive UI polish

## Files You Will Mainly Touch

- `src/Views/pages/register.php`
- `src/Views/pages/re-register.php`
- `src/Views/pages/student-detail.php`
- shared CSS files in `public/assets/css/` if needed

## Done Means

- forms are clear and usable
- derived result messages fit the backend behavior
- detail page can show identity, derived metadata, files, and delete actions cleanly
