<?php
require_once 'conexion.php'; // Ajusta si tu conexión está en otro archivo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $comentario = trim($_POST['comentario']);
    if ($nombre && $comentario) {
        $stmt = $conn->prepare("INSERT INTO comentarios (nombre, comentario) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $comentario);
        $stmt->execute();
        echo "ok";
    } else {
        echo "error";
    }
}
?>