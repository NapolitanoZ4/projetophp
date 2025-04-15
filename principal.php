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
    <link rel="stylesheet" type="text/css" href="principal.css">
</head>
<body>
    <header>
        <div class="info">
            <h2>Bem-vindo, <?php echo $_SESSION['nome']; ?></h2>
            <p>CPF: <?php echo $_SESSION['cpf']; ?></p>
        </div>
        <a href="sair.php" class="sair-btn">Sair</a>
    </header>

    <div class="container">
        <!-- Conteúdo da página -->
        <p>Conteúdo principal da página...</p>
    </div>
</body>
</html>
