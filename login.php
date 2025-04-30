<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>

<div class="caixa-login">
    <h2>Login</h2>
    <form method="post" action="">
        <input type="text" name="cpf" placeholder="CPF" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <input type="submit" value="Entrar">
    </form>
    <p>Não tem conta? <a href="index.php">Cadastre-se</a></p>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include("conexao.php");

        $cpf = $_POST['cpf'];
        $senha = $_POST['senha'];

        // Usa declaração preparada para evitar SQL Injection
        $stmt = $conn->prepare("SELECT nome, cpf FROM usuarios WHERE cpf = ? AND senha = ?");
        $stmt->bind_param("ss", $cpf, $senha); // "ss" significa que os dois parâmetros são strings
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {
            $usuario = $resultado->fetch_assoc();
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['cpf'] = $usuario['cpf'];
            header("Location: principal.php");
            exit;
        } else {
            echo "<p style='color: red;'>CPF ou senha inválidos.</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</div>

</body>
</html>
