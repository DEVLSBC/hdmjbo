<?php
// // DEBUG ERRO PHP
//  ini_set('display_errors', 1);
//  ini_set('display_startup_errors', 1);
//  error_reporting(E_ALL);
session_start();
require __DIR__ . '/../includes/db.php'; 
require __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Retira tudo que não seja número
    $password = trim($_POST['password']);

    // Consulta ao banco
    $stmt = $conn->prepare("SELECT id_usuario, nome_usuario, senha_usuario, cargo, setor_usuario FROM hdmjbo_usuarios WHERE cpf_usuario = :cpf AND ativo = 1");
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['senha_usuario'])) {
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['cpf'] = $cpf;
        $_SESSION['username'] = $user['nome_usuario'];
        $_SESSION['cargo'] = $user['cargo'];
        $_SESSION['setor'] = $user['setor_usuario'];
        registrarLog($conn, $user['id_usuario'], "Usuario {$_SESSION['username']} realizou login com sucesso.");
        header("Location: /hdmjbo/pages/dashboard.php"); // Redireciona para o painel
        exit;
    } else {
        $error = "Usuário ou senha inválidos.";
        echo($error);
        echo "<br><a href='/hdmjbo/pages/logout.php'>Sair</a>";
    }
}
?>