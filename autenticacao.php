<?php
session_start();
include("conexao.php");

$cpf = $_POST['cpf'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE cpf='$cpf' AND senha='$senha'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $usuario = $result->fetch_assoc();
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['cpf'] = $usuario['cpf'];
    header("Location: principal.php");
} else {
    echo "CPF ou senha incorretos!";
}
$conn->close();
?>
