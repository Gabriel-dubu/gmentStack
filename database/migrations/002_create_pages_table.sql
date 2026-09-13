CREATE TABLE IF NOT EXISTS pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(160) NOT NULL UNIQUE,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Página inicial (slug reservado "inicio"): crie/edite pelo painel admin
-- depois de ter um usuário logado. Esse INSERT só garante que a home
-- não fique vazia no primeiro acesso.
INSERT INTO pages (slug, title, content, created_at, updated_at)
VALUES ('inicio', 'Página Inicial', 'Bem-vindo! Edite esse conteúdo pelo painel admin.', NOW(), NOW())
ON DUPLICATE KEY UPDATE slug = slug;
