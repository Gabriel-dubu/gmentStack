ALTER TABLE pages
    ADD COLUMN hero_layout VARCHAR(20) DEFAULT 'centered' AFTER title_highlight;