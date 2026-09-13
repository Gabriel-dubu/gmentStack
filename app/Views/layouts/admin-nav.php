<nav class="admin-nav">
    <a href="<?= e(($basePath ?? '') . '/admin/dashboard') ?>">Dashboard</a>
    <a href="<?= e(($basePath ?? '') . '/admin/pages') ?>">Páginas</a>
    <a href="<?=  e(($basePath ?? '') . '/admin/leads') ?>">Leads</a>
    <form method="POST" action="<?= e(($basePath ?? '') . '/admin/logout') ?>" style="display:inline">
        <?= \App\Core\Csrf::field() ?>
        <button type="submit" class="link-button">Sair</button>
    </form>
</nav>
