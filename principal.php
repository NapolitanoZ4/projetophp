<?php 
include("autenticacao.php");

echo "CPF: ".$_SESSION['cpf'].'<br>';
echo "NOME: ".$_SESSION['nome'].'<br>';
echo "SENHA: ".$_SESSION['senha'].'<br>';
?>