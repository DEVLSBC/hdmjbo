<?php
session_start();
if ($_SESSION['cargo'] !== 'admin') {
    die('Acesso negado.');
}

require '../includes/db.php';

$id_usuario = $_GET['id_usuario'] ?? null;
if ($id_usuario) {
    $stmt = $conn->prepare("UPDATE hdmjbo_usuarios SET ativo = 0 WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
}

header('Location: usuarios.php');
exit();
?>
