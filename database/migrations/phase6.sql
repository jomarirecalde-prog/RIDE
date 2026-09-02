-- User program assignment for account management and proposal prefill

ALTER TABLE users
    ADD COLUMN IF NOT EXISTS program VARCHAR(150) NULL AFTER college_id;
