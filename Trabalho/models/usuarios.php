<?php

class Usuario
{
	private PDO $conexao;

	public function __construct(PDO $conexao)
	{
		$this->conexao = $conexao;
	}

	public function criar(string $nome, string $email, string $senha): int
	{
		$sql = 'INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)';
		$stmt = $this->conexao->prepare($sql);
		$stmt->execute([
			':nome' => $nome,
			':email' => $email,
			':senha' => password_hash($senha, PASSWORD_DEFAULT),
		]);

		return (int) $this->conexao->lastInsertId();
	}

	public function buscarPorId(int $id): ?array
	{
		$stmt = $this->conexao->prepare(
			'SELECT id, nome, email FROM usuarios WHERE id = :id LIMIT 1'
		);
		$stmt->execute([':id' => $id]);
		$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

		return $usuario ?: null;
	}

	public function buscarPorEmail(string $email): ?array
	{
		$stmt = $this->conexao->prepare(
			'SELECT id, nome, email, senha FROM usuarios WHERE email = :email LIMIT 1'
		);
		$stmt->execute([':email' => $email]);
		$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

		return $usuario ?: null;
	}

	public function autenticar(string $email, string $senha): ?array
	{

		$usuario = $this->buscarPorEmail($email);

		if ($usuario === null || !password_verify($senha, $usuario['senha'])) {
			return null;
		}

		unset($usuario['senha']);
		return $usuario;
	}

	public function listar(): array
	{
		$stmt = $this->conexao->query('SELECT id, nome, email FROM usuarios ORDER BY nome');
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function atualizar(int $id, string $nome, string $email): bool
	{
		$stmt = $this->conexao->prepare(
			'UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id'
		);
		return $stmt->execute([':id' => $id, ':nome' => $nome, ':email' => $email]);
	}

	public function atualizarSenha(int $id, string $senha): bool
	{
		$stmt = $this->conexao->prepare('UPDATE usuarios SET senha = :senha WHERE id = :id');
		return $stmt->execute([
			':id' => $id,
			':senha' => password_hash($senha, PASSWORD_DEFAULT),
		]);
	}

	public function excluir(int $id): bool
	{
		$stmt = $this->conexao->prepare('DELETE FROM usuarios WHERE id = :id');
		return $stmt->execute([':id' => $id]);
	}
}
