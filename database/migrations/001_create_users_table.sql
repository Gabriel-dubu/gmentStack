CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'admin',
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Para criar seu primeiro usuário admin, gere o hash com PHP:
-- php -r "echo password_hash('sua-senha', PASSWORD_ARGON2ID);"
-- e insira manualmente:
-- INSERT INTO users (name, email, password, role, created_at)
-- VALUES ('Admin', 'admin@exemplo.com', '$argon2id$...hash...', 'admin', NOW());
