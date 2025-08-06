<?php
// filepath: c:\xampp\htdocs\Lilivan2\actualizar_carrito.php
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header("Location: ini_sesi_form.php");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];

if (!isset($_POST['cantidades']) || !is_array($_POST['cantidades'])) {
    header("Location: carrito.php");
    exit;
}

$conexion = new mysqli('localhost', 'root', '', 'lilivan-v2');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

foreach ($_POST['cantidades'] as $carrito_id => $cantidad) {
    $carrito_id = intval($carrito_id);
    $cantidad = intval($cantidad);
    if ($cantidad > 0) {
        $stmt = $conexion->prepare("UPDATE carrito SET cantidad = ? WHERE id = ? AND cliente_id = ?");
        $stmt->bind_param("iii", $cantidad, $carrito_id, $cliente_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conexion->close();
header("Location: carrito.php");
exit;