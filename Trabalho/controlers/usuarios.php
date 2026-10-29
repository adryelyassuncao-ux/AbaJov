<?php

// Controlador responsável por cadastrar usuários, fazer login e logout.
// Ele recebe requisições POST, valida os dados e redireciona para a página inicial.

// torna o código mais seguro, evitando conversões automáticas de tipos de dados.
declare(strict_types=1);

require_once __DIR__ . '/../config/autoload.php';

// Inicia a sessão do PHP para armazenar informações do usuário logado e mensagens de erro/sucesso.
session_start();

// Inclui o arquivo de configuração do banco de dados, que retorna uma instância de PDO para interagir com o banco.
require_once __DIR__ . '/../config/database.php';

//
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	exit('Método não permitido.');
}

$acao = $_POST['acao'] ?? '';
$nome = trim((string) ($_POST['nome'] ?? ''));
$email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
$senha = (string) ($_POST['senha'] ?? '');

try {
	/** @var PDO $pdo */
	switch ($acao) {
		case 'cadastrar':
			if ($nome === '' || $email === false || strlen($senha) < 6) {
				throw new InvalidArgumentException('Preencha os dados corretamente.');
			}

			$stmt = $pdo->prepare(
				'INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)'
			);
			$stmt->execute([
				':nome' => $nome,
				':email' => $email,
				':senha' => password_hash($senha, PASSWORD_DEFAULT),
			]);
			$_SESSION['mensagem'] = 'Usuário cadastrado com sucesso.';
			break;

		case 'login':

			if ($email === false || $senha === '') {
				throw new InvalidArgumentException('Informe e-mail e senha.');
			}

			$stmt = $pdo->prepare('SELECT id, nome, email, senha FROM usuarios WHERE email = :email LIMIT 1');
			$stmt->execute([':email' => $email]);
			$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

			if (!$usuario || !password_verify($senha, $usuario['senha'])) {
				throw new InvalidArgumentException('E-mail ou senha inválidos.');
			}

			session_regenerate_id(true);
			$_SESSION['usuario'] = [
				'id' => $usuario['id'],
				'nome' => $usuario['nome'],
				'email' => $usuario['email'],
			];
			$_SESSION['mensagem'] = 'Login realizado com sucesso.';
			break;

		case 'logout':
			$_SESSION = [];
			session_destroy();
			break;
//meu código
			case 'editar':
            if ($nome === '' || $email === false) {
                throw new InvalidArgumentException('Preencha os dados corretamente.');
            }
            $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
            if (!$id) {
                throw new InvalidArgumentException('ID inválido.');
            }

            $stmt = $pdo->prepare('UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id');
            $stmt->execute([':id' => $id, ':nome' => $nome, ':email' => $email]);
            $_SESSION['mensagem'] = 'Usuário atualizado com sucesso.';
            break;

        case 'excluir':
            $id = filter_var($_POST['id'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);
            if (!$id) {
                throw new InvalidArgumentException('ID inválido.');
            }

            $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $_SESSION['mensagem'] = 'Usuário excluído com sucesso.';
            break;
//termina aqui
		default:
			throw new InvalidArgumentException('Ação inválida.');
	}

	header('Location: ../index.php');
	exit;
} catch (InvalidArgumentException $e) {
	$_SESSION['erro'] = $e->getMessage();
	header('Location: ../index.php');
	exit;
} catch (PDOException $e) {
	error_log($e->getMessage());
	http_response_code(500);
	exit('Erro interno do servidor.');
}

