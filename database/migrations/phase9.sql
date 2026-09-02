-- Supporting document attachments for scholarly output records

CREATE TABLE IF NOT EXISTS scholarly_attachments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    record_type ENUM('published_paper', 'paper_presentation') NOT NULL,
    record_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NULL,
    file_size INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_scholarly_attachments_record (record_type, record_id)
) ENGINE=InnoDB;
