<?php
        $host = 'localhost';
        $db = 'hdmjbo';
        $user = 'root';
        $password = 'kop123';
        try {
            $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
        ?>