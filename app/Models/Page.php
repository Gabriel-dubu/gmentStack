<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Page
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT id, slug, title, template, image, updated_at FROM pages ORDER BY updated_at DESC');

        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM pages WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $page = $stmt->fetch();

        return $page ?: null;
    }

    public static function findBySlug(string $slug): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM pages WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);

        $page = $stmt->fetch();

        return $page ?: null;
    }

    public static function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $pdo = Database::getConnection();
        $sql = 'SELECT COUNT(*) FROM pages WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO pages
                (slug, template, title, subtitle, hero_eyebrow, title_highlight, hero_layout, trust_badges, content,
                 cta_text, cta_link, cta_secondary_text, cta_secondary_link, whatsapp, topbar_phone,
                 topbar_note, map_address, privacy_notice, image, image_caption, logo, created_at, updated_at)
             VALUES
                (:slug, :template, :title, :subtitle, :hero_eyebrow, :title_highlight, :hero_layout, :trust_badges, :content,
                 :cta_text, :cta_link, :cta_secondary_text, :cta_secondary_link, :whatsapp, :topbar_phone,
                 :topbar_note, :map_address, :privacy_notice, :image, :image_caption, :logo, NOW(), NOW())'
        );
        $stmt->execute([
            'slug' => $data['slug'],
            'template' => $data['template'],
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
            'hero_eyebrow' => $data['hero_eyebrow'],
            'title_highlight' => $data['title_highlight'],
            'hero_layout' => $data['hero_layout'],
            'trust_badges' => $data['trust_badges'],
            'content' => $data['content'],
            'cta_text' => $data['cta_text'],
            'cta_link' => $data['cta_link'],
            'cta_secondary_text' => $data['cta_secondary_text'],
            'cta_secondary_link' => $data['cta_secondary_link'],
            'whatsapp' => $data['whatsapp'],
            'topbar_phone' => $data['topbar_phone'],
            'topbar_note' => $data['topbar_note'],
            'map_address' => $data['map_address'],
            'privacy_notice' => $data['privacy_notice'],
            'image' => $data['image'],
            'image_caption' => $data['image_caption'],
            'logo' => $data['logo'],
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'UPDATE pages SET
                slug = :slug, template = :template, title = :title, subtitle = :subtitle,
                hero_eyebrow = :hero_eyebrow, title_highlight = :title_highlight, hero_layout = :hero_layout, trust_badges = :trust_badges,
                content = :content, cta_text = :cta_text, cta_link = :cta_link,
                cta_secondary_text = :cta_secondary_text, cta_secondary_link = :cta_secondary_link,
                whatsapp = :whatsapp, topbar_phone = :topbar_phone, topbar_note = :topbar_note,
                map_address = :map_address, privacy_notice = :privacy_notice,
                image = :image, image_caption = :image_caption, logo = :logo,
                updated_at = NOW()
             WHERE id = :id'
        );
        $stmt->execute([
            'slug' => $data['slug'],
            'template' => $data['template'],
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
            'hero_eyebrow' => $data['hero_eyebrow'],
            'title_highlight' => $data['title_highlight'],
            'hero_layout' => $data['hero_layout'],
            'trust_badges' => $data['trust_badges'],
            'content' => $data['content'],
            'cta_text' => $data['cta_text'],
            'cta_link' => $data['cta_link'],
            'cta_secondary_text' => $data['cta_secondary_text'],
            'cta_secondary_link' => $data['cta_secondary_link'],
            'whatsapp' => $data['whatsapp'],
            'topbar_phone' => $data['topbar_phone'],
            'topbar_note' => $data['topbar_note'],
            'map_address' => $data['map_address'],
            'privacy_notice' => $data['privacy_notice'],
            'image' => $data['image'],
            'image_caption' => $data['image_caption'],
            'logo' => $data['logo'],
            'id' => $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM pages WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
