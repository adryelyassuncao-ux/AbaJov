<div class="card shadow-sm border-0">
    <div class="card-body p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Bem-vindo, <?= $usuario['nome'] ?>!</h1>
                <p class="text-secondary mb-0"><?= $usuario['email'] ?></p>
            </div>

            <form action="../controlers/usuarios.php" method="post">
                <input type="hidden" name="acao" value="logout" />
                <button type="submit" class="btn btn-outline-danger">Sair</button>
            </form>
        </div>

        <p class="mb-0">Você está autenticado e a página de login fica ocultada enquanto a sessão está ativa.</p>
    </div>
</div>