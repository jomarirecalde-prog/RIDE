-- Profile picture (avatar) for account settings

ALTER TABLE users
    ADD COLUMN IF NOT EXISTS avatar_path VARCHAR(255) NULL AFTER signature_path;
