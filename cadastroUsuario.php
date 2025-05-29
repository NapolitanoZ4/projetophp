<?php
session_start();

// CONEXÃO  ---------------------------------------------------------------------------------------------------
$host = "localhost";  // deixa desse jeito
$user = "root";      // deixa desse jeito
$pass = "";          // deixa desse jeito
$db = "cadastro_filmes";    // coloca o nome do seu servidor !!!

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// AUTENTICAÇÃO  -----------------------------------------------------------------------------------------------
if (isset($_POST['login'])) {
    $cpf = $_POST["cpf"];
    $senha = $_POST["senha"];

    if (empty($cpf) || empty($senha)) {
        die("Insira CPF e Senha");
    }

    $sql = "SELECT nome FROM usuarios WHERE cpf=? AND senha=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $cpf, $senha);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["cpf"] = $cpf;
        $_SESSION["senha"] = $senha;
        $_SESSION["nome"] = $row['nome'];
        header("Location: cadastroUsuario.php");
        exit;
    } else {
        echo "CPF ou Senha inválidos.";
    }
}

// SALVAR USUÁRIO  ------------------------------------------------------------------------------------------------
if (isset($_POST['salvar'])) {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];

    $sql = "INSERT INTO usuarios (cpf, nome, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $cpf, $nome, $senha);
    if ($stmt->execute()) {
        header("Location: cadastroUsuario.php");
        exit;
    } else {
        echo "Erro ao salvar.";
    }
}

// ALTERAR USUÁRIO -------------------------------------------------------------------------------------------------
if (isset($_POST['alterar'])) {
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];                              // !!!   tenho que fazer a parte de editar, mais ta funcionando   !!!
    $nome = $_POST['nome'];
    $cpfantigo = $_POST['cpfAnterior'];

    $sql = "UPDATE usuarios SET cpf=?, senha=?, nome=? WHERE cpf=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $cpf, $senha, $nome, $cpfantigo);
    if ($stmt->execute()) {
        header("Location: cadastroUsuario.php");
        exit;
    } else {
        echo "Erro ao alterar.";
    }
}

// APAGAR USUÁRIO  --------------------------------------------------------------------------------------------------
if (isset($_POST['apagar'])) {
    $cpf = $_POST['cpf'];
    $sql = "DELETE FROM usuarios WHERE cpf=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cpf);
    if ($stmt->execute()) {
        header("Location: cadastroUsuario.php");
        exit;
    } else {
        echo "Erro ao apagar.";
    }
}

// LOGOUT  -----------------------------------------------------------------------------------------------------------
if (isset($_GET['sair'])) {
    session_destroy();
    header("Location: cadastroUsuario.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastro Unificado</title>
    <link rel="stylesheet" href="cadastroUsuario.css">
</head>

<body>

<?php if (!isset($_SESSION['nome'])) : ?>
    <header>
        <h2>Login</h2>
    </header>
    <main>
        <form method="post">
            <input type="text" name="cpf" placeholder="CPF">
            <input type="password" name="senha" placeholder="Senha">
            <button type="submit" name="login" class="buton">Entrar</button>
        </form>
    </main>
<?php else : ?>
    <header>
        <span>Bem-vindo, <?= $_SESSION['nome']; ?></span>
        <a href="principal.php"><img src="imagens/sair.png" alt="Sair"></a>
    </header>

    <main>
        <nav>
            <h2 class="title menu">Menu</h2>
            <p><a href="cadastroUsuario.php">Cadastrar Usuário</a></p>
            <p><a href="cadastroFilme.php">Cadastrar Filmes</a></p>
            <p><a href="#">Item 3</a></p>
            <p><a href="#">Item 4</a></p>
            <p><a href="#">Item 5</a></p>
            <p><a href="#">Item 6</a></p>
        </nav>

        <div class="content">
            <h2 class="title main">Cadastro de Usuário</h2>

            <form method="post">
                <div class="cpf"><input type="text" name="cpf" placeholder="CPF"></div>
                <div class="nome"><input type="text" name="nome" placeholder="Nome"></div>
                <div class="senha"><input type="password" name="senha" placeholder="Senha"></div>
                <button type="submit" name="salvar" class="buton enviar">Enviar</button>
            </form>

            <h2 class="title main">Usuários Cadastrados</h2>
            <table>
                <tr>
                    <td>Nome</td>
                    <td>CPF</td>
                    <td>Senha</td>
                    <td>Ações</td>
                </tr>
                <?php
                $sql = "SELECT nome, cpf, senha FROM usuarios";
                $resultado = $conn->query($sql);
                while ($row = $resultado->fetch_assoc()) :
                ?>
                    <tr>
                        <form method="post">
                            <input type="hidden" name="cpfAnterior" value="<?= $row['cpf']; ?>">
                            <td><div class="nome"><input type="text" name="nome" value="<?= $row['nome']; ?>"></div></td>
                            <td><div class="cpf"><input type="text" name="cpf" value="<?= $row['cpf']; ?>"></div></td>
                            <td><div class="senha"><input type="text" name="senha" value="<?= $row['senha']; ?>"></div></td>
                            <td>
                                <button type="submit" name="alterar" class="buton">Alterar</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="cpf" value="<?= $row['cpf']; ?>">
                            <button type="submit" name="apagar" class="buton">Apagar</button>
                        </form>
                            </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
<?php endif; ?>

</body>

</html>

