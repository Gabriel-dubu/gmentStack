<?php

use App\Core\Csrf;

$titulo = 'Login - Admin';
require __DIR__ . '/../layouts/header.php';
?>

<div class="row rowCardLogin">
    <div class="col-md-4 cardLogin">
        <main class="page page-login">
            <h1>Painel Administrativo</h1>

            <?php if (!empty($error)): ?>
                <p class="alert alert-error flash"><?= e($error) ?></p>
            <?php endif; ?>

            <form method="POST" action="<?= e(($basePath ?? '') . '/admin/login') ?>">
                <?= Csrf::field() ?>

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required autofocus>

                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>

                <div class="col-md-8 cardLogin">
                    <a href="#" class="linkRememberPass">Esqueci minha senha</a>
                    <button type="submit" class="btnLogin">Entrar no sistema</button>
                </div>
                <div style="clear: both;"></div>
            </form>
        </main>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>