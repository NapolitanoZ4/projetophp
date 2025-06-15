<?php
session_start();
include("autenticacao.php");
include("conexao.php");

// CONEXÃO
$host = "localhost";
$user = "root";
$pass = "";
$db = "cadastro_filmes";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// INSERIR FILME
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'inserir') {
    $nome = $_POST['nome'];
    $ano = $_POST['ano'];
    $genero = $_POST['genero'];

    // Validação de ano
    if (!is_numeric($ano) || $ano < 1888 || $ano > 2025) {
        echo "<script>alert('Ano inválido! O ano deve estar entre 1888 e 2025.'); window.location.href='cadastroFilme.php';</script>";
        exit;
    }

    $sql = "INSERT INTO filmes (filme, nome, genero_id, ano) VALUES (NULL, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sii", $nome, $genero, $ano);
        $stmt->execute();
        header("Location: cadastroFilme.php");
        exit;
    }
}

// ALTERAR FILME
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'alterar') {
    $filme = $_POST['filme'];
    $nome = $_POST['nome'];
    $ano = $_POST['ano'];
    $generoId = $_POST['genero'];

    // Validação de ano
    if (!is_numeric($ano) || $ano < 1888 || $ano > 2025) {
        echo "<script>alert('Ano inválido! O ano deve estar entre 1888 e 2025.'); window.location.href='cadastroFilme.php';</script>";
        exit;
    }

    $sql = "UPDATE filmes SET nome=?, genero_id=?, ano=? WHERE filme=?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("siii", $nome, $generoId, $ano, $filme);
        $stmt->execute();
        header("Location: cadastroFilme.php");
        exit;
    }
}

// APAGAR FILME
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'apagar') {
    $filme = $_POST['filme'];
    $sql = "DELETE FROM filmes WHERE filme = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $filme);
        $stmt->execute();
        header("Location: cadastroFilme.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Filmes</title>
    <link rel="stylesheet" href="cadastroFilme.css">
</head>
<body>
    <header>
        <div class="logo">
            <span>Bem-vindo, <?= $_SESSION['nome']; ?></span>
        </div>
        <div class="user-area">
            <form action="principal.php" method="post">
                <button type="submit">Sair</button>
            </form>
        </div>
    </header>

    <main>
        <nav>
            <h2 class="title menu">Menu</h2>
            <p><a href="cadastroUsuario.php">Cadastrar Usuário</a></p>
            <p><a href="cadastroFilme.php">Cadastrar Filmes</a></p>
            <p><a href="item3.php">Item 3</a></p>
        </nav>

        <div class="content">
            <h2 class="title">Cadastro de Filmes</h2>
            <form action="cadastroFilme.php" method="post">
                <input type="hidden" name="acao" value="inserir">
                <div class="nome">
                    <input type="text" name="nome" placeholder="Nome" required>
                </div>
                <div class="ano">
                    <input type="number" name="ano" placeholder="Ano" min="1888" max="2025" required>
                </div>
                <select name="genero" required>
                    <option value="">Selecione um Gênero</option>
                    <?php
                    $generos = $conn->query("SELECT * FROM generos WHERE status = 1");
                    while ($row = $generos->fetch_assoc()) {
                        echo "<option value='{$row['genero_id']}'>{$row['genero']}</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Cadastrar" class="buton enviar">
            </form>

            <h2 class="title">Filmes Cadastrados</h2>
            <table>
                <tr>
                    <th>Nome</th>
                    <th>Gênero</th>
                    <th>Ano</th>
                    <th colspan="2">Ações</th>
                </tr>
                <?php
                $filmes = $conn->query("SELECT filmes.*, generos.genero FROM filmes INNER JOIN generos ON filmes.genero_id = generos.genero_id");
                while ($row = $filmes->fetch_assoc()) :
                ?>
                    <tr>
                        <form method="post" action="cadastroFilme.php">
                            <input type="hidden" name="acao" value="alterar">
                            <input type="hidden" name="filme" value="<?= $row['filme'] ?>">
                            <td><input type="text" name="nome" value="<?= $row['nome'] ?>" required></td>
                            <td>
                                <select name="genero" required>
                                    <?php
                                    $generos = $conn->query("SELECT * FROM generos WHERE status = 1");
                                    while ($g = $generos->fetch_assoc()) {
                                        $selected = $g['genero_id'] == $row['genero_id'] ? 'selected' : '';
                                        echo "<option value='{$g['genero_id']}' $selected>{$g['genero']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><input type="number" name="ano" min="1888" max="2025" value="<?= $row['ano'] ?>" required></td>
                            <td><input type="submit" value="Alterar" class="buton"></td>
                        </form>
                        <form method="post" action="cadastroFilme.php">
                            <input type="hidden" name="acao" value="apagar">
                            <input type="hidden" name="filme" value="<?= $row['filme'] ?>">
                            <td><input type="submit" value="Apagar" class="buton"></td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
</body>
</html>
