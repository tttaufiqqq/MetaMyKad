# Metadata Extraction And Analysis Rules

**Parent:** [Implementation specs](./README.md)  
**Related:**
- [Upload contract](./04-file-upload-and-storage-contract.md)
- [Search contracts](./06-retrieval-and-search-contracts.md)

Status: Draft  
Type: Implementation target

## IC Parsing Rules

Input format: 12 digits without separators.

Derived fields:

- `date_of_birth`
- `gender`
- `state_of_birth`
- `age`

Rules:

- gender is derived from the last digit parity
- state is derived from the middle state code mapping table
- age is calculated from date of birth at request time

## Email Classification Rules

- `student` for institutional student domains
- `work` for known company or organization domains
- `personal` as the default fallback

The exact domain list should live in one helper file, not scattered in templates.

## PDF Rules

- extract text after the file is stored successfully
- write extracted text into `file_metadata.extracted_text`
- if extraction fails, store an empty string and surface a warning for staff review

## Photo Rules

Store photo-related CBR fields:

- `photo_category`
- `bg_color_variance`
- `dominant_bg_color`
- `dominant_expression`
- `expression_confidence`

Initial implementation may use deterministic heuristics if full expression analysis is not
available yet, but field names must stay stable.

## Audio Rules

Store:

- `audio_duration_sec`
- `audio_duration_tier`
- `audio_bitrate`

Suggested tiers:

- `short`: under 60 seconds
- `medium`: 60 to 300 seconds
- `long`: over 300 seconds

## Video Rules

Store:

- `video_resolution`
- `video_resolution_tier`
- `video_duration_sec`

Suggested tiers:

- `SD`
- `HD`
- `FHD`
- `UHD`
