<?php
session_start();
if ($_SESSION['cargo'] !== 'admin') {
    die('Acesso negado.');
}

require '../includes/db.php';
require '../includes/functions.php';

$id_usuario = $_GET['id_usuario'] ?? null;
if ($id_usuario) {
    $stmt = $conn->prepare("UPDATE hdmjbo_usuarios SET ativo = 0 WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
}

registrarLog($conn, $_SESSION['id_usuario'], "Desabilitou o usuário com ID {$_GET['id_usuario']}.");
header('Location: usuarios.php');
exit();
?>
