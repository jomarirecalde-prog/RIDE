-- Faculty signature upload for proposal forms

ALTER TABLE users
    ADD COLUMN IF NOT EXISTS signature_path VARCHAR(255) NULL AFTER campus_id;
