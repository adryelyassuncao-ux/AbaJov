<?php

declare(strict_types=1);

session_start();
require_once __DIR__ . '/config/autoload.php';

if (isset($_SESSION['usuario'])) {
    header('Location: home.php');
    exit;
}

if (isset($_COOKIE['remember_user'], $_COOKIE['remember_token'])) {
    $email = filter_var(trim((string) $_COOKIE['remember_user']), FILTER_VALIDATE_EMAIL);

    if ($email !== false) {
        $pdo = require __DIR__ . '/config/database.php';
        $stmt = $pdo->prepare('SELECT id, nome, email, senha FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario !== false && $usuario !== null) {
            $tokenEsperado = hash_hmac('sha256', $email, 'login-php-app');

            if (hash_equals((string) $_COOKIE['remember_token'], $tokenEsperado)) {
                $_SESSION['usuario'] = [
                    'id' => (int) $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                ];
                $_SESSION['s_token'] = bin2hex(random_bytes(16));
                header('Location: home.php');
                exit;
            }
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <main class="card shadow-sm border-0" style="width: min(100%, 420px);">
        <div class="card-body p-4 p-md-5">
            <?php if (isset($_SESSION['erro'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['erro'], ENT_QUOTES, 'UTF-8'); ?></div>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>

            <h1 class="h3 mb-4 text-center">Entrar</h1>

            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required  value="admin@example.com"/>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required  value="123456"/>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember-me" value="1" id="remember-me" />
                    <label class="form-check-label" for="remember-me">Manter conectado</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </main>
</body>
</html>