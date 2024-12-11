<?php
session_start();
if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../includes/db.php';

    $nome = htmlspecialchars($_POST['nome']);
    $user_cpf = preg_replace('/\D/', '', $_POST['cpf']); // Remove tudo que não for número
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $cargo = htmlspecialchars($_POST['cargo']);
    $setor = htmlspecialchars($_POST['setor']);

    if (!validarCPF($user_cpf)) {
        die("CPF inválido. Por favor, insira um CPF válido.");
    }

    try {
        $sql = "INSERT INTO hdmjbo_usuarios (nome_usuario, cpf_usuario, senha_usuario, cargo, setor_usuario) VALUES (:nome, :cpf, :senha, :cargo, :setor)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['nome' => $nome, 'cpf' => $user_cpf, 'senha' => $senha, 'cargo' => $cargo, 'setor' => $setor]);
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
    <title>Cadastrar Usuário</title>
</head>

<body class="cadastro_body">
    <a href="dashboard.php">Voltar</a>
    <form method="POST" action="" class="cadastro_form">
        <h1 class="cadastro_titulo">Cadastro de Usuário</h1>
        <label for="nome" class="cadastro_label">Nome do Usuário:</label>
        <input class="cadastro_input" type="text" id="nome" name="nome" required>

        <label for="cpf" class="cadastro_label">CPF do Usuário:</label>
        <input class="cadastro_input" type="text" id="cpf" name="cpf" maxlength="14" required oninput="formatCPF(this)">

        <label for="senha" class="cadastro_label">Senha do Usuário:</label>
        <input class="cadastro_input" type="password" id="senha" name="senha" required>

        <label for="setor" class="cadastro_label">Setor do Usuário:</label>
        <select id="setor" name="setor" required>
            <option value="cleitos">Central de Leitos</option>
            <option value="daf">DAF</option>
            <option value="farmacia">Farmacia</option>
            <option value="faturamento">Faturamento</option>
            <option value="unipe">Unipe</option>
            <option value="acolhimento">Acolhimento</option>
        </select>

        <label for="cargo" class="cadastro_label">Cargo do Usuário:</label>
        <select id="cargo" name="cargo" required>
            <option value="chefe">Chefe</option>
            <option value="user">Usuário</option>
        </select>

        <button type="submit">Cadastrar</button>
    </form>
</body>
<script src="../assets/js/app.js"></script>
</html>