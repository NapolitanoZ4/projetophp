<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>

<div class="caixa-login">
    <h2>Cadastro</h2>
    <form method="post" action="">
        <input type="text" name="nome" placeholder="Nome" required><br>
        <input type="text" name="sobrenome" placeholder="Sobrenome" required><br> <!-- Campo para sobrenome -->
        <input type="text" name="cpf" placeholder="CPF" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <input type="submit" value="Cadastrar">
    </form>
    <p>Já tem uma conta? <a href="login.php">Faça login</a></p>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include("conexao.php");

        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome']; // Pegando o sobrenome
        $cpf = $_POST['cpf'];
        $senha = $_POST['senha'];

        // Verifica se já existe um usuário com o mesmo CPF
        $verifica = $conn->query("SELECT * FROM usuarios WHERE cpf = '$cpf'");

        if ($verifica->num_rows > 0) {
            echo "<p style='color: red;'>CPF já cadastrado. Faça login ou use outro CPF.</p>";
        } else {
            $sql = "INSERT INTO usuarios (nome, sobrenome, cpf, senha) VALUES ('$nome', '$sobrenome', '$cpf', '$senha')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['nome'] = $nome;
                $_SESSION['cpf'] = $cpf;
                header("Location: principal.php");
                exit;
            } else {
                echo "<p style='color: red;'>Erro ao cadastrar: " . $conn->error . "</p>";
            }
        }

        $conn->close();
    }
    ?>
</div>

</body>
</html>