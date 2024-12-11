<?php
session_start();
if ($_SESSION['cargo'] !== 'admin') {
    die('Acesso negado.');
}

require '../includes/db.php';

$stmt = $conn->query("SELECT id_usuario, nome_usuario, cpf_usuario, cargo, setor_usuario FROM hdmjbo_usuarios WHERE cargo != 'admin'");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/imagens/favicon.png">
    <title>Gerenciamento de Usuários</title>
</head>
<body>
    <a href="dashboard.php">Voltar</a>
    <h1>Gerenciar Usuários</h1>
    <a href="cadastro.php">Cadastrar Novo Usuário</a>
    <table border="1">
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Cargo</th>
            <th>Setor</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['nome_usuario']) ?></td>
                <td><?= htmlspecialchars($usuario['cpf_usuario']) ?></td>
                <td><?= htmlspecialchars($usuario['cargo']) ?></td>
                <td><?= htmlspecialchars($usuario['setor_usuario']) ?></td>
                <td>
                    <a href="editar_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>">Editar</a>
                    <a href="deletar_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
