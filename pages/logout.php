<?php
session_start();
require '../includes/functions.php';
require '../includes/db.php';

if (isset($_SESSION['id_usuario'])) {
    registrarLog($conn, $_SESSION['id_usuario'], 'Logout realizado com sucesso.');
}

session_destroy();
header("Location: /hdmjbo/index.php");
exit;
?>