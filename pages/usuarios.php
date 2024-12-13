<?php
session_start();
if ($_SESSION['cargo'] !== 'admin') {
    die('Acesso negado.');
}

require '../includes/db.php';

$stmt = $conn->query("SELECT id_usuario, nome_usuario, cpf_usuario, cargo, setor_usuario, ativo FROM hdmjbo_usuarios WHERE cargo != 'admin'");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/imagens/favicon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Gerenciamento de Usuários</title>
</head>
<body class="usuarios_Body">
    <main class="usuarios_Dash">
        <div class="usuarios_Header">
            <a href="dashboard.php">Voltar</a>
            <h1 class="usuarios_Titulo">Gerenciar Usuários</h1>
            <a class="usuarios_Cadastrar" href="cadastro.php">Cadastrar Usuário</a>
        </div>
        <hr>
        <table class="usuarios_Tabela">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Cargo</th>
                    <th>Setor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr class="lista_usuarios_tabela">
                        <td><?= htmlspecialchars($usuario['nome_usuario']) ?></td>
                        <td><?= htmlspecialchars($usuario['cpf_usuario']) ?></td>
                        <td><?= htmlspecialchars($usuario['cargo']) ?></td>
                        <td><?= htmlspecialchars($usuario['setor_usuario']) ?></td>
                        <td>
                            <a href="editar_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <?php if ($usuario['ativo']): ?>
                                <a href="desabilitar_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>" title="Desabilitar">
                                    <i class="fas fa-user-slash"></i>
                                </a>
                            <?php else: ?>
                                <a href="habilitar_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>" title="Habilitar">
                                    <i class="fas fa-user-check"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
