<?php
session_start();
if ($_SESSION['cargo'] !== 'admin') {
    die('Acesso negado.');
}

require '../includes/db.php';
require '../includes/functions.php';

// Obtém o ID do usuário para edição
$id_usuario = $_GET['id_usuario'] ?? null;

if (!$id_usuario) {
    die('ID do usuário não fornecido.');
}

// Obtém os dados do usuário do banco
$stmt = $conn->prepare("SELECT nome_usuario, cpf_usuario, senha_usuario, cargo, setor_usuario FROM hdmjbo_usuarios WHERE id_usuario = :id_usuario");
$stmt->execute(['id_usuario' => $id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die('Usuário não encontrado.');
}

// Atualiza os dados do usuário após submissão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_usuario = $_POST['nome_usuario'];
    $cpf_usuario = preg_replace('/\D/', '', $_POST['cpf_usuario']); // Remove não numéricos
    $cargo = $_POST['cargo'];
    $setor_usuario = $_POST['setor_usuario'];
    $nova_senha = !empty($_POST['senha_usuario']) ? password_hash($_POST['senha_usuario'], PASSWORD_DEFAULT) : null;

    // Validação do CPF
    if (!validarCPF($cpf_usuario)) {
        $erro = "CPF inválido. Por favor, insira um CPF válido.";
    } else {
        // Atualiza o banco de dados
        $sql = "UPDATE hdmjbo_usuarios SET nome_usuario = :nome_usuario, cpf_usuario = :cpf_usuario, cargo = :cargo, setor_usuario = :setor_usuario";
        $params = [
            'nome_usuario' => $nome_usuario,
            'cpf_usuario' => $cpf_usuario,
            'cargo' => $cargo,
            'setor_usuario' => $setor_usuario,
            'id_usuario' => $id_usuario
        ];

        if ($nova_senha) {
            $sql .= ", senha_usuario = :senha_usuario";
            $params['senha_usuario'] = $nova_senha;
        }

        $sql .= " WHERE id_usuario = :id_usuario";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        registrarLog($conn, $_SESSION['id_usuario'], "Editou o usuário com ID {$_GET['id_usuario']}.");
        header('Location: usuarios.php');
        exit();
    }
}

// Função para validar CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/imagens/favicon.png">
    <title>Editar Usuário</title>
</head>
<body>
    <a class="voltar_button_usuario" href="usuarios.php">Voltar</a>
    <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
    <form method="POST" action="" class="cadastro_form">
        <h1 class="cadastro_titulo">Editar Usuário</h1>
        <label for="nome_usuario">Nome:</label>
        <input type="text" id="nome_usuario" name="nome_usuario" value="<?= htmlspecialchars($usuario['nome_usuario']) ?>" required>

        <label for="cpf_usuario">CPF:</label>
        <input type="text" id="cpf_usuario" name="cpf_usuario" maxlength="14" value="<?= htmlspecialchars($usuario['cpf_usuario']) ?>" required oninput="formatCPF(this)">

        <label for="cargo">Cargo:</label>
        <select id="cargo" name="cargo" required>
            <option value="chefe" <?= $usuario['cargo'] === 'chefe' ? 'selected' : '' ?>>Chefe</option>
            <option value="user" <?= $usuario['cargo'] === 'user' ? 'selected' : '' ?>>Usuário</option>
        </select>

        <label for="setor_usuario" class="cadastro_label">Setor do Usuário (Verifique o setor):</label>
        <select id="setor_usuario" name="setor_usuario" required>
            <option value="cleitos">Central de Leitos</option>
            <option value="daf">DAF</option>
            <option value="farmacia">Farmacia</option>
            <option value="faturamento">Faturamento</option>
            <option value="unipe">Unipe</option>
            <option value="acolhimento">Acolhimento</option>
        </select>

        <label for="senha_usuario">Nova Senha (deixe em branco para não alterar):</label>
        <input type="password" id="senha_usuario" name="senha_usuario">

        <button type="submit">Salvar Alterações</button>
    </form>
</body>
<script src="../assets/js/app.js"></script>
</html>
