<?php
$titulo = $page['title'];
require __DIR__ . '/../layouts/header.php';
?>

<header class="hero hero-landing">
    <div class="hero-inner">
        <h1><?= e($page['title']) ?></h1>
        <?php if (!empty($page['subtitle'])): ?>
            <p class="hero-subtitle"><?= e($page['subtitle']) ?></p>
        <?php endif; ?>
    </div>
</header>

<main class="page page-landing">
    <?php if (!empty($page['image'])): ?>
        <img
            src="<?= e(($basePath ?? '') . '/uploads/pages/' . $page['image']) ?>"
            alt="<?= e($page['title']) ?>"
            class="page-image">
    <?php endif; ?>

    <div class="page-content"><?= renderPlainTextBlock($page['content']) ?></div>

    <?php foreach ($sections as $section): ?>
        <?php $textColor = contrastTextColor($section['bg_color'] ?? null); ?>

        <?php if (($section['layout'] ?? '') === 'checklist'): ?>
            <section class="content-section">
                <div
                    class="checklist-card"
                    style="<?php if (!empty($section['bg_color'])): ?>background-color: <?= e($section['bg_color']) ?>;<?php endif; ?> color: <?= e($textColor) ?>;">
                    <?php if (!empty($section['title'])): ?>
                        <h2><?= e($section['title']) ?></h2>
                    <?php endif; ?>
                    <?= renderChecklist($section['content']) ?>
                </div>
            </section>
        <?php else: ?>
            <section
                class="content-section content-section-<?= e($section['layout']) ?>"
                style="<?php if (!empty($section['bg_color'])): ?>background-color: <?= e($section['bg_color']) ?>;<?php endif; ?> color: <?= e($textColor) ?>;">
                <div class="content-section-inner">
                    <?php if (!empty($section['image'])): ?>
                        <img
                            src="<?= e(($basePath ?? '') . '/uploads/pages/' . $section['image']) ?>"
                            alt="<?= e($section['title'] ?? '') ?>"
                            class="content-section-image">
                    <?php endif; ?>

                    <div class="content-section-text">
                        <?php if (!empty($section['title'])): ?>
                            <h2><?= e($section['title']) ?></h2>
                        <?php endif; ?>
                        <?= renderPlainTextBlock($section['content']) ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>

    <section class="lead-form-section" id="fale-conosco">
        <h2><?= e($page['cta_text'] ?? 'Fale com um especialista agora') ?></h2>

        <?php if (!empty($leadSuccess)): ?>
            <p class="alert alert-success flash"><?= e($leadSuccess) ?></p>
        <?php else: ?>
            <form method="POST" action="<?= e(($basePath ?? '') . '/' . $page['slug'] . '/lead') ?>" class="lead-form">
                <?= \App\Core\Csrf::field() ?>

                <label for="name">Nome</label>
                <input type="text" id="name" name="name" value="<?= e($leadOld['name'] ?? '') ?>" required>
                <?php if (!empty($leadErrors['name'])): ?>
                    <p class="field-error flash"><?= e($leadErrors['name']) ?></p>
                <?php endif; ?>

                <label for="phone">WhatsApp</label>
                <input type="text" id="phone" name="phone" placeholder="(11) 91234-5678" value="<?= e($leadOld['phone'] ?? '') ?>" required>
                <?php if (!empty($leadErrors['phone'])): ?>
                    <p class="field-error flash"><?= e($leadErrors['phone']) ?></p>
                <?php endif; ?>

                <button type="submit" class="button button-gold button-block">Quero ser atendido</button>
            </form>
        <?php endif; ?>
    </section>
</main>


<?php require __DIR__ . '/../layouts/footer.php'; ?>