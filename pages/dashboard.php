<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: /hdmjbo/pages/dashboard.php");
    exit;
}

echo "Bem-vindo, " . htmlspecialchars($_SESSION['username']) . "!";
echo "<br><a href='/hdmjbo/pages/logout.php'>Sair</a>";
?>
