<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "lilivan-v3";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
