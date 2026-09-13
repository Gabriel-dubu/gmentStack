CREATE TABLE IF NOT EXISTS page_sections(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL,
    position TINYINT UNSIGNED NOT NULL DEFAULT 0,
    title VARCHAR(200) DEFAULT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    bg_color VARCHAR(7) DEFAULT NULL,
    layout VARCHAR(20) NOT NULL DEFAULT 'text-only', --text-only | image-left | image-right
    CONSTRAINT fk_page_sections_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;