-- Migration 002: widen ic_number to VARCHAR(64) for SHA-256 hash storage
-- Run this BEFORE running 003_fix_server_ic_hashes.sql
-- Safe to re-run: MODIFY COLUMN is idempotent if already VARCHAR(64)

ALTER TABLE students MODIFY COLUMN ic_number VARCHAR(64);
ALTER TABLE registration_history MODIFY COLUMN ic_number VARCHAR(64);
