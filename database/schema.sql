CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Exemplo de criação de um admin (rode o hash via PHP: password_hash('sua-senha', PASSWORD_BCRYPT))
-- INSERT INTO users (name, email, password_hash, created_at)
-- VALUES ('Admin', 'admin@exemplo.com', '$2y$10$...hash...', NOW());
