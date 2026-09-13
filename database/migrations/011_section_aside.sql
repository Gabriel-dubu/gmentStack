ALTER TABLE page_sections
    ADD COLUMN aside_title VARCHAR(200) DEFAULT NULL AFTER content,
    ADD COLUMN aside_content TEXT DEFAULT NULL AFTER aside_title;