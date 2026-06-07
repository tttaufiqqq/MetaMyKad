# MetaMyKad - Product Requirements Document

**Parent:** [Product docs](./README.md)  
**Related:**
- [Requirements](./requirements.md)
- [ERD](../architecture/erd.md)
- [Registration flow](../implementation/system-design/03-registration-and-reregistration-flow.md)

Status: Draft  
Type: Target behavior

## Product Summary

MetaMyKad is a multimedia student registry that accepts a Malaysian IC number, derives
demographic attributes from it, stores optional uploaded media, extracts searchable metadata,
and supports attribute-based, text-based, and content-based retrieval.

## Problem

Typical student registration forms stop at basic text fields and raw file upload. They do not:

- derive structured attributes from MyKad automatically
- classify student email type consistently
- extract useful metadata from uploaded multimedia
- support meaningful search across text and media characteristics

## Users

- Students submitting or updating their registration
- Lecturers searching records for academic or demo use
- Administrators validating metadata, deleting files, and reviewing history

## Core Goals

- register one student record from a single form
- derive date of birth, gender, state of birth, and age from IC number
- classify email as `personal`, `student`, or `work`
- store photo, audio, PDF, and video metadata
- support ABR, TBR, and CBR from one database
- assign badges from file completeness
- preserve registration history for re-registration

## In Scope

- plain PHP server-rendered pages
- MySQL relational database
- filesystem-based upload storage
- PDF text extraction
- photo, audio, and video metadata analysis
- staff dashboard and search pages
- safe record updates and file deletion

## Out Of Scope

- mobile app clients
- cloud-only architecture requirements
- social login
- advanced face recognition identity matching
- machine-learning model training inside the PHP app itself

## Success Criteria

- a student can submit one registration with zero to four files
- the system derives IC and email attributes automatically
- staff can query by ABR, TBR, and CBR using dedicated search screens
- re-registration updates data without losing audit history
- deletion removes metadata safely and tracks cleanup outcomes
