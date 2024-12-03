<?php
// Verifica se o sistema já foi configurado
if (file_exists(__DIR__ . '/includes/db.php')) {
    die('O Banco de Dados já está configurado. Apague o arquivo "setup.php" da pasta raiz ou remova o arquivo "db.php" para reconfigurar.');
}

// Processa o formulário ao enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtém os dados do formulário
        $db_host = trim($_POST['db_host']);
        $db_user = trim($_POST['db_user']);
        $db_password = trim($_POST['db_password']);
        $db_name = trim($_POST['db_name']);
        $admin_user = trim($_POST['admin_user']);
        $admin_password = trim($_POST['admin_password']);

        // Validação básica
        if (empty($db_host) || empty($db_user) || empty($db_name) || empty($admin_user) || empty($admin_password)) {
            throw new Exception("Todos os campos são obrigatórios.");
        }

        // Conecta ao banco de dados
        $conn = new mysqli($db_host, $db_user, $db_password);
        if ($conn->connect_error) {
            throw new Exception("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        // Cria o banco de dados se não existir
        if (!$conn->query("CREATE DATABASE IF NOT EXISTS $db_name")) {
            throw new Exception("Erro ao criar banco de dados: " . $conn->error);
        }

        // Seleciona o banco de dados
        $conn->select_db($db_name);

        // Cria a tabela de usuários
        $create_table_sql = "
            CREATE TABLE IF NOT EXISTS hdmjbo_usuarios (
                id_usuario INT AUTO_INCREMENT PRIMARY KEY,
                nome_usuario VARCHAR(50) NOT NULL UNIQUE,
                senha_usuario VARCHAR(255) NOT NULL,
                cargo ENUM('admin', 'chefe', 'user') DEFAULT 'user',
                data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        if (!$conn->query($create_table_sql)) {
            throw new Exception("Erro ao criar tabela de usuários: " . $conn->error);
        }

        // Insere o usuário admin
        $admin_hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        $insert_admin_sql = "INSERT INTO hdmjbo_usuarios (nome_usuario, senha_usuario, cargo) VALUES (?, ?, 'admin')";
        $stmt = $conn->prepare($insert_admin_sql);
        $stmt->bind_param('ss', $admin_user, $admin_hashed_password);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao criar usuário admin: " . $stmt->error);
        }

        // Salva a configuração em db.php
        $dbContent = "<?php\n";
        $dbContent .= "\$host = '$db_host';\n";
        $dbContent .= "\$dbname = '$db_name';\n";
        $dbContent .= "\$username = '$db_user';\n";
        $dbContent .= "\$password = '$db_password';\n\n";
        $dbContent .= "try {\n";
        $dbContent .= "    \$conn = new PDO(\"mysql:host=\$host;dbname=\$dbname\", \$username, \$password);\n";
        $dbContent .= "    \$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n";
        $dbContent .= "} catch (PDOException \$e) {\n";
        $dbContent .= "    die(\"Erro de conexão: \" . \$e->getMessage());\n";
        $dbContent .= "}\n";
        $dbContent .= "?>";
        if (file_put_contents(__DIR__ . '/includes/db.php', $dbContent)) {
            echo "Arquivo de configuração criado com sucesso.";
        } else {
            echo "Erro ao criar o arquivo de configuração.";
        }

        // Redireciona após sucesso
        header('Location: ./index.php');
        exit;
    } catch (Exception $e) {
        echo "<p style='color: red;'>Erro: " . $e->getMessage() . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalação Sistema Interno</title>
    <link rel="stylesheet" href="/hdmjbo/assets/css/style.css">
    <link rel="icon" href="/hdmjbo/assets/imagens/favicon.png">
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

            <label for="admin_user">Usuário Admin:</label>
            <input type="text" name="admin_user" id="admin_user" required>

            <label for="admin_password">Senha Admin:</label>
            <input type="password" name="admin_password" id="admin_password" required>

            <button type="submit">Salvar Configuração e Criar Tabelas</button>
        </form>
    </div>
</body>
</html>
