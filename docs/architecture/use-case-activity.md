# MetaMyKad - Use Case And Activity Summary

**Parent:** [Architecture docs](./README.md)  
**Related:**
- [Flow of events](./flow-of-events.md)
- [Page specs](../pages/README.md)

Status: Draft  
Type: Target behavior

## Actors

- Visitor (any classmate, no login required)
- Owner (logged-in student viewing their own record)

## Visitor Use Cases

- submit a new registration
- upload up to four multimedia files
- log in with matric number and password
- view the dashboard, student list, student detail, and history pages
- search records by attribute, text, or content filters

## Owner Use Cases

- update their own registration after logging in
- delete their own uploaded files after logging in
- log out

## Activity Overview

```text
Student submits form
   ->
derive IC and email attributes
   ->
validate optional uploads
   ->
store file metadata and analysis
   ->
compute badge
   ->
write history
   ->
staff dashboard and search consume stored metadata
```
