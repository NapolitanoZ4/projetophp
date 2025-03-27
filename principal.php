<?php 
include("autenticacao.php");

echo "CPF: ".$_SESSION['cpf'].'<br>';
echo "NOME: ".$_SESSION['nome'].'<br>';
echo "SENHA: ".$_SESSION['senha'].'<br>';
?>

<div style="width: 800px; margin: 0 auto;";>
        <div style="min-height: 100px; width: 100%; background-color: #4caf50";>
            <div style="width: 50%; float: left">
                <span style="padding-left: 10px;"> Olá <?=$_SESSION['cpf'];?></span>
            </div>

            <div style="width: 50%; float:left; text-align:right;">
                <span style="background-color:blue; margin-right: 10px;"><a href="sair.php">
                    <font color="black">SAIR</font></a></span>
            </div>
        </div>
        <div id="menu" style="width: 200px; background-color: #f4f4f4; min-height: 400px; float: left;">
            <h2>MENU</h2>
            
        </div>

    </div>