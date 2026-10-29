<?php

session_start();
require_once __DIR__ . '/config/autoload.php';
// Verifica se o usuário já está autenticado. Se estiver, redireciona para a página inicial.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}


$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$lembrar = !empty($_POST['remember-me']);


// Valida o e-mail e a senha recebidos do formulário de login. Se forem inválidos, redireciona de volta para a página de login com uma mensagem de erro.
if ($email === false || $senha === '') {
    $_SESSION['erro'] = '1-Informe e-mail e senha válidos.';
    header('Location: index.php');
    exit;
}

// Cria uma instância do PDO para interagir com o banco de dados e uma instância do modelo de usuário para autenticar o usuário.
$pdo = require __DIR__ . '/config/database.php';

// Cria uma instância do modelo de usuário para autenticar o usuário.
$usuarioModel = new Usuario($pdo);

// Autentica o usuário usando o modelo de usuário. Se a autenticação falhar, redireciona de volta para a página de login com uma mensagem de erro.
$usuario = $usuarioModel->autenticar($email, $senha);


if ($usuario === null) {
    $_SESSION['erro'] = '2-E-mail ou senha inválidos.';
    header('Location: index.php');
    exit;
}

// Se a autenticação for bem-sucedida, inicia a sessão do usuário e redireciona para a página inicial.
session_regenerate_id(true);
$_SESSION['usuario'] = [
    'id' => (int) $usuario['id'],
    'nome' => $usuario['nome'],
    'email' => $usuario['email'],
];
$_SESSION['s_token'] = bin2hex(random_bytes(16));
$_SESSION['dir'] = C:\Users\ana.pires125\Desktop\Trabalho;

// Se o usuário optou por ser lembrado, cria cookies de "lembrar-me" com o e-mail e um token seguro. Caso contrário, remove os cookies existentes.
if ($lembrar) {
    $token = hash_hmac('sha256', $email, 'login-php-app');
    setcookie('remember_user', $email, time() + 60 * 60 * 24 * 30, '/');
    setcookie('remember_token', $token, time() + 60 * 60 * 24 * 30, '/');
} else {
    setcookie('remember_user', '', time() - 3600, '/');
    setcookie('remember_token', '', time() - 3600, '/');
}

// Redireciona o usuário para a página inicial após o login bem-sucedido.
header('Location: home.php');
exit;
