<?php 

echo "Nome: ".$_POST['nome']."<br>"; //$_POST ARMAZENA DADOS ENVIADOS ATRAVES DO METODO POST POR UM FORMULARIO
echo "Idade: ".$_POST['idade']."<br>";//Por exemplo, se você tem um campo no formulário com o atributo name='idade', você acessaria esse valor utilizando $_post['idade'].
echo "<hr>";
print_r($_POST);
?>