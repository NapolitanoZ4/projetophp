<?php
session_start();

// CONEXÃO ------------------------------------------------------------------------------------
$host = "localhost";
$user = "root";
$pass = "";
$db = "cadastro_filmes";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// VARIÁVEL PARA MENSAGEM  ----------------------------------------------------------------------
$mensagem = "";

// FUNÇÕES DE VALIDAÇÃO  ------------------------------------------------------------------------
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11) return false;
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}

function validarSenha($senha) {
    return strlen($senha) >= 8 &&
           preg_match('/[A-Z]/', $senha) &&
           preg_match('/[a-z]/', $senha) &&
           preg_match('/[0-9]/', $senha);
}

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


// LOGIN ---------------------------------------------------------------------------------------------
if (isset($_POST['login'])) {
    $cpf = $_POST["cpf"];
    $senha = $_POST["senha"];
    $email = $_POST["email"];

    if (empty($cpf) || empty($senha) || empty($email)) {
        $mensagem = "Insira CPF, senha e e-mail.";
    } elseif (!validarCPF($cpf)) {
        $mensagem = "CPF inválido.";
    } elseif (!validarSenha($senha)) {
        $mensagem = "Senha inválida. Deve conter ao menos 8 caracteres com letras maiúsculas, minúsculas e números.";
    } elseif (!validarEmail($email)) {
        $mensagem = "E-mail inválido.";
    } else {
        $sql = "SELECT nome FROM usuarios WHERE cpf=? AND senha=? AND email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $cpf, $senha, $email);
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
            $mensagem = "Credenciais inválidas.";
        }
    }
}


// CADASTRO ------------------------------------------------------------------------------------
if (isset($_POST['salvar'])) {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];

    if (!validarCPF($cpf) || !validarSenha($senha) || !validarEmail($email)) {
        $mensagem = "Dados inválidos. Verifique CPF, senha e e-mail.";
    } else {
        $sql = "INSERT INTO usuarios (cpf, nome, senha, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $cpf, $nome, $senha, $email);
        if ($stmt->execute()) {
            $mensagem = "Usuário cadastrado com sucesso!";
        } else {
            $mensagem = "Erro ao salvar. Verifique se o CPF já existe.";
        }
    }
}


// ALTERAÇÃO --------------------------------------------------------------------------------------
if (isset($_POST['alterar'])) {
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpfantigo = $_POST['cpfAnterior'];

    if (!validarCPF($cpf) || !validarSenha($senha) || !validarEmail($email)) {
        $mensagem = "Dados inválidos.";
    } else {
        $sql = "UPDATE usuarios SET cpf=?, senha=?, nome=?, email=? WHERE cpf=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $cpf, $senha, $nome, $email, $cpfantigo);
        if ($stmt->execute()) {
            $mensagem = "Usuário alterado com sucesso!";
        } else {
            $mensagem = "Erro ao alterar.";
        }
    }
}

// EXCLUSÃO -------------------------------------------------------------------------------------------
if (isset($_POST['apagar'])) {
    $cpf = $_POST['cpf'];
    $sql = "DELETE FROM usuarios WHERE cpf=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cpf);
    if ($stmt->execute()) {
        $mensagem = "Usuário apagado com sucesso!";
    } else {
        $mensagem = "Erro ao apagar.";
    }
}

// LOGOUT ----------------------------------------------------------------------------------------------
if (isset($_GET['sair'])) {
    session_destroy();
    header("Location: cadastroUsuario.php");
    exit;
}


// HTML ------------------------------------------------------------------------------------------------
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
            <input type="text" name="cpf" placeholder="CPF" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <button type="submit" name="login" class="buton">Entrar</button>
        </form>

        <?php if (!empty($mensagem)) : ?>
            <?php
                $classeMensagem = stripos($mensagem, 'sucesso') !== false ? 'mensagem sucesso' : 'mensagem erro';
            ?>
            <div class="<?= $classeMensagem ?>"><?= $mensagem ?></div>
        <?php endif; ?>
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
        </nav>

        <div class="content">
            <h2 class="title main">Cadastro de Usuário</h2>

            <?php if (!empty($mensagem)) : ?>
                <?php
                    $classeMensagem = stripos($mensagem, 'sucesso') !== false ? 'mensagem sucesso' : 'mensagem erro';
                ?>
                <div class="<?= $classeMensagem ?>"><?= $mensagem ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="cpf"><input type="text" name="cpf" placeholder="CPF" required></div>
                <div class="nome"><input type="text" name="nome" placeholder="Nome" required></div>
                <div class="senha"><input type="password" name="senha" placeholder="Senha" required></div>
                <div class="email"><input type="email" name="email" placeholder="E-mail" required></div>
                <button type="submit" name="salvar" class="buton enviar">Enviar</button>
            </form>

            <h2 class="title main">Usuários Cadastrados</h2>
            <table>
                <tr>
                    <td>Nome</td>
                    <td>CPF</td>
                    <td>Senha</td>
                    <td>Email</td>
                    <td>Ações</td>
                </tr>
                <?php
                $sql = "SELECT nome, cpf, senha, email FROM usuarios";
                $resultado = $conn->query($sql);
                while ($row = $resultado->fetch_assoc()) :
                ?>
                    <tr>
                        <form method="post">
                            <input type="hidden" name="cpfAnterior" value="<?= $row['cpf']; ?>">
                            <td><div class="nome"><input type="text" name="nome" value="<?= $row['nome']; ?>"></div></td>
                            <td><div class="cpf"><input type="text" name="cpf" value="<?= $row['cpf']; ?>"></div></td>
                            <td><div class="senha"><input type="text" name="senha" value="<?= $row['senha']; ?>"></div></td>
                            <td><div class="email"><input type="email" name="email" value="<?= $row['email']; ?>"></div></td>
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

<script>
  setTimeout(() => {
    const msg = document.querySelector('.mensagem');
    if (msg) msg.remove();
  }, 4000);
</script>

</body>
</html>
