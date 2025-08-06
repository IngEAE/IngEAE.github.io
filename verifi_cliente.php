<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    if ($campo === 'documento') {
        $sql = "SELECT id FROM clientes WHERE numero_documento = ?";
    } elseif ($campo === 'email') {
        $sql = "SELECT id FROM clientes WHERE correo_electronico = ?";
    } else {
        echo json_encode(["existe" => false]);
        exit;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $valor);
    $stmt->execute();
    $stmt->store_result();

    $existe = $stmt->num_rows > 0;
    echo json_encode(["existe" => $existe]);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["existe" => false]);
}
?>

