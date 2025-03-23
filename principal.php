<?php 
session_start();
include("autenticacao.php");

echo "USUÁRIO: ".$_SESSION['usuario'].'<br>';
echo "SENHA: ".$_SESSION['senha'];

?>