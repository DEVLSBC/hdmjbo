<?php
session_start();
if ($_SESSION['cargo'] !== 'admin') {
    die('Acesso negado.');
}

require '../includes/db.php';
$stmt = $conn->query("SELECT l.*, u.nome_usuario FROM hdmjbo_logs l JOIN hdmjbo_usuarios u ON l.id_usuario = u.id_usuario ORDER BY l.data_hora DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/imagens/favicon.png">
    <title>Logs do Sistema</title>
</head>
<body class="logs_Body">
    <main class="logs_Dash">
        <div class="logs_Header">
            <a href="dashboard.php">Voltar</a>
            <h1 class="logs_admin_titulo">Logs do Sistema</h1>
            <div></div>
        </div>
        <table border="1" class="logs_admin">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>IP</th>
                    <th>Atividade</th>
                    <th>Data e Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['nome_usuario']) ?></td>
                        <td><?= htmlspecialchars($log['ip_address']) ?></td>
                        <td><?= htmlspecialchars($log['atividade']) ?></td>
                        <td><?= htmlspecialchars($log['data_hora']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
