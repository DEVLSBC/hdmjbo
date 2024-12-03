<?php
// DEBUG ERRO PHP
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
require __DIR__ . '/../includes/db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Consulta ao banco
    $stmt = $conn->prepare("SELECT senha_usuario FROM hdmjbo_usuarios WHERE nome_usuario = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['senha_usuario'])) {
        $_SESSION['username'] = $username;
        header("Location: /hdmjbo/pages/painel.php"); // Redireciona para o painel
        exit;
    } else {
        $error = "Usuário ou senha inválidos.";
        echo($error);
        echo "<br><a href='/hdmjbo/pages/logout.php'>Sair</a>";
    }
}
?>