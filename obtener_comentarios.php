<?php
require_once 'conexion.php';
$result = $conn->query("SELECT nombre, comentario, fecha FROM comentarios ORDER BY fecha DESC LIMIT 20");
$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row;
}
header('Content-Type: application/json');
echo json_encode($comentarios);
?>