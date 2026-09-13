<?php $titulo = $page['title']; require __DIR__ . '/../layouts/header.php'; ?>

<main class="page">
    <h1><?= e($page['title']) ?></h1>

    <?php if (!empty($page['image'])): ?>
        <img
            src="<?= e(($basePath ?? '') . '/uploads/pages/' . $page['image']) ?>"
            alt="<?= e($page['title']) ?>"
            class="page-image"
        >
    <?php endif; ?>

    <!--
        O conteúdo é escapado com e() e depois convertido para <br> com nl2br().
        Isso permite quebras de linha sem abrir a porta para HTML/JS arbitrário
        (stored XSS) digitado no campo de conteúdo do admin.
    -->
    <div class="page-content"><?= nl2br(e($page['content'])) ?></div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
