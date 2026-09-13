<?php $titulo = 'Páginas - Admin';
require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/admin-nav.php'; ?>

<main class="page">
    <div class="page-header">
        <h1>Páginas</h1>
        <a href="<?= e(($basePath ?? '') . '/admin/pages/create') ?>" class="button">Nova página</a>
    </div>

    <?php if (!empty($success)): ?>
        <p class="alert alert-success flash"><?= e($success) ?></p>
    <?php endif; ?>

    <?php if (empty($pages)): ?>
        <p>Nenhuma página cadastrada ainda.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Slug</th>
                    <th>Atualizado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $p): ?>
                    <tr>
                        <td><?= e($p['title']) ?></td>
                        <td>/<?= e($p['slug']) ?></td>
                        <td><?= e($p['updated_at']) ?></td>
                        <td class="actions">

                            <form
                                method="POST"
                                action="<?= e(($basePath ?? '') . '/admin/pages/' . $p['id'] . '/delete') ?>"
                                onsubmit="return confirm('Remover esta página? Essa ação não pode ser desfeita.');">
                                <a class="btn btn-warning" href="<?= e(($basePath ?? '') . '/admin/pages/' . $p['id'] . '/edit') ?>">Editar</a>
                                <?= \App\Core\Csrf::field() ?>
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>