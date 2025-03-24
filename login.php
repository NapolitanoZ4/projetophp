<?php 

include("conexao.php");

$cpf=$_POST['cpf'];
$senha=$_POST['senha'];

if (!isset($_POST['cpf']) || $_POST['cpf'] == '') {
    die("favor informar um CPF");
}

if (!isset($_POST['senha']) || $_POST['senha'] == '') {
    die("favor informar uma senha");
}

$sql = "select nome from usuarios where cpf='$cpf' and senha='$senha'";

$resultado =  $conn->query($sql);
$row = $resultado->fetch_assoc();

if (isset($row) && $row['nome'] != '') {
    session_start();

    $_SESSION["nome"] =$cpf;
    $_SESSION["senha"] =$senha;
    $_SESSION["nome"] = $row['nome'];
    header("Location: principal.php");
}else{
    echo "SENHA INCORRETA";
}




?>