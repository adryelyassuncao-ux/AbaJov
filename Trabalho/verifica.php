<?php

declare(strict_types=1);

// Inicia a sessão do PHP para armazenar informações do usuário logado e mensagens de erro/sucesso.
session_start();

// Se o usuário já estiver autenticado, não é necessário verificar novamente.
if (isset($_SESSION['usuario'])) {
    return;
}


// Verifica se os cookies de "lembrar-me" estão presentes.
if (isset($_COOKIE['remember_user'], $_COOKIE['remember_token'])) {

    // Valida o e-mail do cookie e busca o usuário correspondente no banco de dados.
    $email = filter_var(trim((string) $_COOKIE['remember_user']), FILTER_VALIDATE_EMAIL);

    // Se o e-mail for válido, busca o usuário no banco de dados.
    if ($email !== false) {
        $pdo = require __DIR__ . '/config/database.php';
        $stmt = $pdo->prepare('SELECT id, nome, email, senha FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o usuário for encontrado, verifica se o token do cookie corresponde ao token esperado.
        if ($usuario !== false && $usuario !== null) {
            $tokenEsperado = hash_hmac('sha256', $email, 'login-php-app');

            // Se o token do cookie corresponder ao token esperado, autentica o usuário e inicia a sessão.
            if (hash_equals((string) $_COOKIE['remember_token'], $tokenEsperado)) {
                $_SESSION['usuario'] = [
                    'id' => (int) $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                ];
                $_SESSION['s_token'] = bin2hex(random_bytes(16));
                return;
            }
        }
    }
}

// Se a autenticação automática falhar, redireciona o usuário para a página de login.
header('Location: index.php');
exit;