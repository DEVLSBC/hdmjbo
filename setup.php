<?php
if (file_exists(__DIR__ . '/../includes/db.php')) {
    die('O sistema já está configurado. Remova o arquivo "db.php" para reconfigurar.');
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
    // Obtém os dados do formulário
    $db_host = trim($_POST['db_host']);
    $db_user = trim($_POST['db_user']);
    $db_password = trim($_POST['db_password']);
    $db_name = trim($_POST['db_name']);
    $admin_cpf = preg_replace('/\D/', '', $_POST['admin_cpf']); // Remove tudo que não for número
    $admin_user = trim($_POST['admin_user']);
    $admin_password = password_hash(trim($_POST['admin_password']), PASSWORD_DEFAULT);

    if (!validarCPF($admin_cpf)) {
        die("CPF inválido. Por favor, insira um CPF válido.");
    }

    try {
        $conn = new mysqli($db_host, $db_user, $db_password);
        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        $conn->query("CREATE DATABASE IF NOT EXISTS $db_name");
        $conn->select_db($db_name);

        $conn->query("
            CREATE TABLE IF NOT EXISTS hdmjbo_usuarios (
                id_usuario INT AUTO_INCREMENT PRIMARY KEY,
                nome_usuario VARCHAR(50) NOT NULL UNIQUE,
                cpf_usuario VARCHAR(11) NOT NULL UNIQUE,
                senha_usuario VARCHAR(255) NOT NULL,
                cargo ENUM('admin', 'chefe', 'user') DEFAULT 'user',
                data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $stmt = $conn->prepare("INSERT INTO hdmjbo_usuarios (cpf_usuario, nome_usuario, senha_usuario, cargo) VALUES (?, ?, ?, 'admin')");
        $stmt->bind_param('sss', $admin_cpf, $admin_user, $admin_password);
        $stmt->execute();

        $db_config = "<?php
        \$host = '$db_host';
        \$db = '$db_name';
        \$user = '$db_user';
        \$password = '$db_password';
        try {
            \$conn = new PDO(\"mysql:host=\$host;dbname=\$db\", \$user, \$password);
            \$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException \$e) {
            die(\"Erro de conexão: \" . \$e->getMessage());
        }
        ?>";

        file_put_contents(__DIR__ . '/includes/db.php', $db_config);
        echo "Configuração concluída com sucesso!";
        header('Location: ./index.php');
        exit;
    } catch (Exception $e) {
        die("Erro ao configurar: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/hdmjbo/assets/css/style.css">
    <link rel="icon" href="/hdmjbo/assets/imagens/favicon.png">
    <title>Configuração do Sistema</title>
</head>
<body>
    <div class="setup-container">
        <h1>Instalação Sistema Interno</h1>
        <form method="POST">
            <label for="db_host">Host do Banco de Dados:</label>
            <input type="text" name="db_host" id="db_host" required>

            <label for="db_user">Usuário do Banco de Dados:</label>
            <input type="text" name="db_user" id="db_user" required>

            <label for="db_password">Senha do Banco de Dados:</label>
            <input type="password" name="db_password" id="db_password">

            <label for="db_name">Nome do Banco de Dados:</label>
            <input type="text" name="db_name" id="db_name" required>

            <label for="admin_cpf">CPF do Administrador:</label>
            <input type="text" id="admin_cpf" name="admin_cpf" maxlength="14" required oninput="formatCPF(this)">

            <label for="admin_user">Usuário Admin:</label>
            <input type="text" name="admin_user" id="admin_user" required>

            <label for="admin_password">Senha Admin:</label>
            <input type="password" name="admin_password" id="admin_password" required>

            <button type="submit">Salvar Configuração e Criar Tabelas</button>
        </form>
    </div>
</body>
<script src="/hdmjbo/assets/js/app.js"></script>
</html>
