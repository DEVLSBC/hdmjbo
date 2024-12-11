<?php
session_start();
if ($_SESSION['cargo'] !== 'admin') {
    die('Acesso negado.');
}

require '../includes/db.php';

$id_usuario = $_GET['id_usuario'] ?? null;

try{
    if ($id_usuario) {
        $stmt = $conn->prepare("DELETE FROM hdmjbo_usuarios WHERE id_usuario = :id_usuario AND cargo != 'admin'");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
    }
    header('Location: usuarios.php');
} catch (PDOException $e) {
    echo "Erro ao excluir usuario: " . $e->getMessage();
}

?>
