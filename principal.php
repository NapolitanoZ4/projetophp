<?php 
session_start();
if (!isset($_SESSION['cpf'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="principal.css">
</head>
<body>
    <header>
        <div class="info">
            <h2>Bem-vindo, <?php echo $_SESSION['nome']; ?></h2>
            <p>CPF: <?php echo $_SESSION['cpf']; ?></p>
        </div>
        <a href="sair.php" class="sair-btn">Sair</a>
    </header>

    <div class="painel">
        <div class="menu-lateral">
            <h2>Menu</h2>
            <a href="cadastroUsuario.php">Cadastro Usuarios</a>  
            <a href="cadastroFilme.php">Cadastro Filmes</a>
            <a href="#">Item 3</a>
            <a href="#">Item 4</a>
            <a href="#">Item 5</a>
            <a href="#">Item 6</a>
        </div>

        <div class="conteudo-principal">
            <h2>Bem-vindo à área administrativa</h2>
            <p>Aqui você pode gerenciar usuários, acessar dados e navegar pelas funções do sistema.</p>
        </div>
    </div>
</body>
</html>
