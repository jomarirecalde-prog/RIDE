-- Co-author invitations: faculty must accept before gaining proposal access

CREATE TABLE IF NOT EXISTS proposal_coauthor_invitations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    invitee_user_id INT UNSIGNED NOT NULL,
    invited_by_user_id INT UNSIGNED NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    responded_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uq_proposal_invitee (proposal_id, invitee_user_id),
    INDEX idx_invitee_status (invitee_user_id, status),
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE,
    FOREIGN KEY (invitee_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (invited_by_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
