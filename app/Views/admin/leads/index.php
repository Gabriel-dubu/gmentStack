<?php $titulo = 'Leads - Admin';
require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/admin-nav.php'; ?>

<main class="page">
    <h1>Leads recebidos</h1>

    <?php if (!empty($success)): ?>
        <p class="alert alert-success flash"><?= e($success) ?></p>
    <?php endif; ?>

    <?php if (empty($leads)): ?>
        <p>Nenhum lead capturado ainda.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Empresa</th>
                    <th>WhatsApp</th>
                    <th>E-mail</th>
                    <th>Situação</th>
                    <th>Página</th>
                    <th>Recebido em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leads as $lead): ?>
                    <tr>
                        <td><?= e($lead['name']) ?></td>
                        <td><?= e($lead['company'] ?? '—') ?></td>
                        <td><?= e($lead['phone']) ?></td>
                        <td><?= e($lead['email'] ?? '—') ?></td>
                        <td><?= e($lead['situation'] ?? '—') ?></td>
                        <td><?= e($lead['page_title'] ?? '—') ?></td>
                        <td><?= e($lead['created_at']) ?></td>
                        <td class="actions">
                            <form
                                method="POST"
                                action="<?= e(($basePath ?? '') . '/admin/leads/' . $lead['id'] . '/delete') ?>"
                                onsubmit="return confirm('Remover este lead?');"
                                style="display:inline">
                                <?= \App\Core\Csrf::field() ?>
                                <button type="submit" class="link-button link-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>