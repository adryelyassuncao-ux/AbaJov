<?php

declare(strict_types=1);

require_once __DIR__ . '/config/autoload.php';
require_once __DIR__ . '/verifica.php';

// Inicia a sessão do PHP para armazenar informações do usuário logado.
$usuario = $_SESSION['usuario'] ?? ['nome' => 'Usuário'];
?>

<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Página inicial</title>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
    <div class="container py-5">

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="./">HOME</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="?pg=usuario">Usuário</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Pricing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <hr>

        <?php
        $pg = $_GET['pg'] ?? 'home';

        include __DIR__ . "/views/{$pg}.php";
        
        ?>

        
        </div>
    </div>
</body>

</html>