<?php

$titulo = 'Dashboard - Admin';
require __DIR__ . '/../layouts/header.php';
?>

<?php require __DIR__ . '/../layouts/admin-nav.php'; ?>

<main class="page">
    <h1>Bem-vindo, <?= e($user['name'] ?? '') ?></h1>
    <p><a href="<?= e(($basePath ?? '') . '/admin/pages') ?>">Gerenciar páginas de conteúdo</a></p>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
