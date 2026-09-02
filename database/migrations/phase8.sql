-- Faculty published papers and paper presentations

CREATE TABLE IF NOT EXISTS published_papers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(500) NOT NULL,
    authors TEXT NULL,
    journal_name VARCHAR(255) NOT NULL,
    publication_date DATE NULL,
    publication_year SMALLINT UNSIGNED NULL,
    doi VARCHAR(120) NULL,
    indexing VARCHAR(120) NULL,
    status ENUM('published', 'accepted', 'in_press') NOT NULL DEFAULT 'published',
    link VARCHAR(500) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_published_papers_user (user_id),
    INDEX idx_published_papers_year (publication_year)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS paper_presentations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(500) NOT NULL,
    conference_name VARCHAR(255) NOT NULL,
    presentation_type ENUM('oral', 'poster', 'virtual', 'other') NOT NULL DEFAULT 'oral',
    presentation_date DATE NULL,
    location VARCHAR(255) NULL,
    is_international TINYINT(1) NOT NULL DEFAULT 0,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_paper_presentations_user (user_id),
    INDEX idx_paper_presentations_date (presentation_date)
) ENGINE=InnoDB;
