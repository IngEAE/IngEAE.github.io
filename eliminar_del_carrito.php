<?php
// filepath: c:\xampp\htdocs\Lilivan2\eliminar_del_carrito.php
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header("Location: ini_sesi_form.php");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$carrito_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($carrito_id > 0) {
    $conexion = new mysqli('localhost', 'root', '', 'lilivan-v2');
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    $stmt = $conexion->prepare("DELETE FROM carrito WHERE id = ? AND cliente_id = ?");
    $stmt->bind_param("ii", $carrito_id, $cliente_id);
    $stmt->execute();
    $stmt->close();
    $conexion->close();
}

header('Location: carrito.php');
exit;