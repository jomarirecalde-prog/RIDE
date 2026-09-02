-- Direct messaging between faculty and monitoring staff roles

CREATE TABLE IF NOT EXISTS direct_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender_id INT UNSIGNED NOT NULL,
    recipient_id INT UNSIGNED NOT NULL,
    body TEXT NOT NULL,
    read_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_dm_recipient_unread (recipient_id, read_at),
    INDEX idx_dm_participants (sender_id, recipient_id, created_at)
) ENGINE=InnoDB;
