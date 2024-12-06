<?php
session_start();
if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../includes/db.php';

    $nome = htmlspecialchars($_POST['nome']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $cargo = htmlspecialchars($_POST['cargo']);

    try {
        $sql = "INSERT INTO hdmjbo_usuarios (nome_usuario, senha_usuario, cargo) VALUES (:nome, :senha, :cargo)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['nome' => $nome, 'senha' => $senha, 'cargo' => $cargo]);
        echo  "<script>alert('Usuário cadastrado com sucesso!');</script>";
        header('Location: dashboard.php');
    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/imagens/favicon.png">
    <title>Cadastro de Usuário</title>
</head>

<body class="cadastro_body">
    <a href="dashboard.php">Voltar</a>
    <form method="POST" action="" class="cadastro_form">
        <h1 class="cadastro_titulo">Cadastro de Usuário</h1>
        <label for="nome" class="cadastro_label">Nome:</label>
        <input class="cadastro_input" type="text" id="nome" name="nome" required>

        <label for="senha" class="cadastro_label">Senha:</label>
        <input class="cadastro_input" type="password" id="senha" name="senha" required>

        <label for="cargo" class="cadastro_label">Cargo:</label>
        <select id="cargo" name="cargo" required>
            <option value="admin">Admin</option>
            <option value="chefe">Chefe</option>
            <option value="user">Usuário</option>
        </select>

        <button type="submit">Cadastrar</button>
    </form>
</body>

</html>