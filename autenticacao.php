<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("conexao.php");

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se CPF e senha estão definidos no POST
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;
    $senha = isset($_POST['senha']) ? $_POST['senha'] : null;

    if ($cpf && $senha) {
        // Usar prepared statement para evitar SQL Injection
        $sql = "SELECT * FROM usuarios WHERE cpf = ? AND senha = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $cpf, $senha);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['cpf'] = $usuario['cpf'];
            header("Location: principal.php");
            exit; // sempre use exit depois do header para garantir que pare a execução
        } else {
            echo "CPF ou senha incorretos!";
        }
    } else {
        echo "Por favor, informe CPF e senha!";
    }
} else {
    // Caso o acesso não seja via POST (ex: abrir direto o arquivo)
    echo "Acesso inválido.";
}
$conn->close();
?>
