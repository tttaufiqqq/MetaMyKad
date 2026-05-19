CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ic_number VARCHAR(12) NOT NULL UNIQUE,
    matric_number VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    email_category ENUM('personal', 'student', 'work') NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('M', 'F') NOT NULL,
    age INT NOT NULL,
    state_of_birth VARCHAR(50) NOT NULL,
    badge ENUM('Pendaftar', 'Pelajar', 'Aktif', 'Dedikasi', 'Cemerlang') NOT NULL DEFAULT 'Pendaftar',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_students_state (state_of_birth),
    INDEX idx_students_email_category (email_category),
    INDEX idx_students_gender (gender),
    INDEX idx_students_badge (badge)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS file_metadata (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    file_type ENUM('photo', 'audio', 'pdf', 'video') NOT NULL,
    filename VARCHAR(255) NOT NULL,
    stored_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size BIGINT NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    upload_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    extracted_text LONGTEXT NULL,
    FULLTEXT INDEX ft_file_metadata_extracted_text (extracted_text),
    INDEX idx_file_metadata_student_file_type (student_id, file_type),
    CONSTRAINT fk_file_metadata_student
        FOREIGN KEY (student_id) REFERENCES students(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cbr_metadata (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT NOT NULL UNIQUE,
    photo_category ENUM('formal', 'non_formal') NULL,
    bg_color_variance FLOAT NULL,
    dominant_bg_color VARCHAR(50) NULL,
    dominant_expression ENUM('happy', 'sad', 'angry', 'neutral', 'surprised', 'fearful', 'disgusted') NULL,
    expression_confidence FLOAT NULL,
    audio_duration_sec INT NULL,
    audio_duration_tier ENUM('short', 'medium', 'long') NULL,
    audio_bitrate INT NULL,
    video_resolution VARCHAR(50) NULL,
    video_resolution_tier ENUM('SD', 'HD', 'FHD', 'UHD') NULL,
    video_duration_sec INT NULL,
    INDEX idx_cbr_expression (dominant_expression),
    INDEX idx_cbr_photo_category (photo_category),
    INDEX idx_cbr_audio_duration_tier (audio_duration_tier),
    INDEX idx_cbr_video_resolution_tier (video_resolution_tier),
    CONSTRAINT fk_cbr_metadata_file
        FOREIGN KEY (file_id) REFERENCES file_metadata(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tag_name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS file_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT NOT NULL,
    tag_id INT NOT NULL,
    UNIQUE KEY uq_file_tags_file_tag (file_id, tag_id),
    CONSTRAINT fk_file_tags_file
        FOREIGN KEY (file_id) REFERENCES file_metadata(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_file_tags_tag
        FOREIGN KEY (tag_id) REFERENCES tags(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS registration_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ic_number VARCHAR(12) NOT NULL,
    registered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    files_uploaded INT NOT NULL DEFAULT 0,
    badge_at_time VARCHAR(20) NOT NULL,
    action ENUM('new', 'update') NOT NULL,
    INDEX idx_registration_history_ic_time (ic_number, registered_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
