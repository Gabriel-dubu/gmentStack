<?php

    use App\Helpers\Url;
    use App\Helpers\Html;

    $titulo = "Multi-Func";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::escape($titulo ?? 'Meu Projeto'); ?></title>
    <link rel="stylesheet" href="<?= Url::asset('css/app.css') ?>">
</head>

<body>
    <header>
        <nav>
            <a href="<?= BASE_PATH ?>/">Início</a>
            <a href="<?= BASE_PATH ?>/recuperacao-fiscal">Recuperação Fiscal</a>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Meu Projeto</p>
    </footer>
</body>

</html>