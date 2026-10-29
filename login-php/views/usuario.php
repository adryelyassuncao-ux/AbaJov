<?php

$acao = $_POST['acao'] ?? '';

   

if ($acao === 'Cadastrar') {

    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $nivel_acesso = $_POST['nivel_acesso'] ?? '';



    

    $pdo = require_once $_SESSION['dir'] . '/config/database.php';

    $usuario = new Usuario($pdo);

    $r = $usuario->criar($nome, $email, $senha, (int) $nivel_acesso);
    
    var_dump($r);

}


?>

<h1>USUÁRIO</h1>
<form action="home.php?pg=usuario" method="post">
    <label class="form-label" for="nome">Nome:</label>
    <input class="form-control" type="text" id="nome" name="nome" required>
    <br>
    <label class="form-label" for="email">Email:</label>
    <input class="form-control" type="email" id="email" name="email" required>
    <br>
    <label class="form-label" for="senha">Senha:</label>
    <input class="form-control" type="password" id="senha" name="senha" required>
    <br>
    <label class="form-label" for="nivel_acesso">Nível de Acesso:</label>
    <select class="form-control" id="nivel_acesso" name="nivel_acesso" required>
        <option value="0">Administrador</option>
        <option value="1">Usuário</option>
        <option value="2">Visitante</option>
    </select>
    <br>

    <input name="acao" class="btn btn-primary" type="submit" value="Cadastrar">

</form>