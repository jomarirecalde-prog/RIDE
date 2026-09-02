CREATE TABLE IF NOT EXISTS milestones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NULL,
    due_date DATE NOT NULL,
    completed_at DATE NULL,
    status ENUM('pending','completed','overdue') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS progress_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    period_label VARCHAR(80) NOT NULL,
    report_type ENUM('quarterly','annual','final') NOT NULL DEFAULT 'quarterly',
    narrative TEXT NULL,
    financial_summary TEXT NULL,
    outputs TEXT NULL,
    status ENUM('draft','submitted','reviewed') NOT NULL DEFAULT 'draft',
    due_date DATE NULL,
    submitted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS report_financial_lines (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    report_id INT UNSIGNED NOT NULL,
    description VARCHAR(200) NOT NULL,
    budgeted DECIMAL(14,2) NOT NULL DEFAULT 0,
    spent DECIMAL(14,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (report_id) REFERENCES progress_reports(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    category ENUM('proposal','report','publication','patent','extension_media','other') NOT NULL DEFAULT 'other',
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(120) NULL,
    file_size INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ip_disclosures (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    disclosure_date DATE NULL,
    status ENUM('draft','filed','approved') NOT NULL DEFAULT 'draft',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS patents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    application_no VARCHAR(80) NULL,
    status ENUM('filed','pending','granted') NOT NULL DEFAULT 'filed',
    filed_date DATE NULL,
    granted_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS technology_transfers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    partner_name VARCHAR(200) NOT NULL,
    transfer_date DATE NULL,
    status ENUM('negotiating','completed') NOT NULL DEFAULT 'negotiating',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS prototypes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    name VARCHAR(200) NOT NULL,
    stage ENUM('concept','alpha','beta','production') NOT NULL DEFAULT 'concept',
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS community_beneficiaries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    group_name VARCHAR(200) NOT NULL,
    beneficiary_count INT UNSIGNED NOT NULL DEFAULT 0,
    location VARCHAR(200) NULL,
    period_year YEAR NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS partner_mous (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    partner_name VARCHAR(200) NOT NULL,
    valid_from DATE NULL,
    valid_until DATE NULL,
    document_id INT UNSIGNED NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE,
    FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS impact_metrics (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT UNSIGNED NOT NULL,
    period_year YEAR NOT NULL,
    people_trained INT UNSIGNED NOT NULL DEFAULT 0,
    income_generated DECIMAL(14,2) NOT NULL DEFAULT 0,
    households_served INT UNSIGNED NOT NULL DEFAULT 0,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255) NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
