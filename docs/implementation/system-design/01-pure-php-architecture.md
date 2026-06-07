# Pure PHP Architecture

**Parent:** [Implementation specs](./README.md)  
**Related:**
- [Setup](../../planning/setup.md)
- [Page specs](../../pages/README.md)

Status: Draft  
Type: Implementation target

## Rule

Use plain PHP with a lightweight separation of concerns. No full-stack framework.

## Suggested Folder Structure

```text
config/
  config.php
public/
  index.php
  register.php
  re-register.php
  dashboard.php
  student-detail.php
  search-attribute.php
  search-text.php
  search-content.php
  history.php
  actions/
src/
  Database/
  Helpers/
  Repositories/
  Services/
  Views/
storage/
  uploads/
    photo/
    audio/
    pdf/
    video/
  tmp/
database/
```

## Separation Rules

- `public/` contains browser entry files and form handlers only
- `src/Repositories/` contains SQL access code
- `src/Services/` contains business rules such as IC parsing and badge calculation
- `src/Views/` or `public/includes/` contains shared layout fragments
- `storage/` contains writable runtime files

## Recommended Shared Services

- `IcParser`
- `EmailClassifier`
- `FileUploadService`
- `MetadataExtractionService`
- `BadgeService`
- `RegistrationService`
- `SearchService`

## Request Flow

```text
public page
   ->
validate request
   ->
call service
   ->
service calls repository
   ->
render PHP template with result or errors
```
