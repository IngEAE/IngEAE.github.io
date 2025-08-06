<?php
session_start();

$producto_id = $_POST['id'] ?? null;
$cantidad = $_POST['cantidad'] ?? 1;

if (empty($producto_id) || !is_numeric($producto_id)) {
    header("Location: productos.php");
    exit;
}

// SIEMPRE carrito en sesión
if (!isset($_SESSION['carrito_tmp'])) {
    $_SESSION['carrito_tmp'] = [];
}
// Buscar si ya existe el producto en el carrito temporal
$encontrado = false;
foreach ($_SESSION['carrito_tmp'] as &$item) {
    if ($item['id'] == $producto_id) {
        $item['cantidad'] += $cantidad;
        $encontrado = true;
        break;
    }
}
unset($item);
if (!$encontrado) {
    // Obtener info del producto para mostrar en el carrito temporal
    $conexion = new mysqli('localhost', 'root', '', 'lilivan-v2');
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    $sql = "SELECT nombre, descripcion, precio, imagen_url FROM productos WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $stmt->bind_result($nombre, $descripcion, $precio, $imagen_url);
    if ($stmt->fetch()) {
        $_SESSION['carrito_tmp'][] = [
            'id' => $producto_id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'imagen_url' => $imagen_url,
            'cantidad' => $cantidad
        ];
    }
    $stmt->close();
    $conexion->close();
}

header("Location: carrito.php");
exit;