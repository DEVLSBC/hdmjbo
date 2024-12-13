<?php 

function registrarLog($conn, $id_usuario, $atividade) {
    $ip_address = $_SERVER['REMOTE_ADDR']; // Captura o IP da máquina
    $stmt = $conn->prepare("INSERT INTO hdmjbo_logs (id_usuario, ip_address, atividade) VALUES (:id_usuario, :ip_address, :atividade)");
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':ip_address' => $ip_address,
        ':atividade' => $atividade
    ]);
}


?>