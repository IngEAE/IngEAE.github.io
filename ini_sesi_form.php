<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $documento = $conn->real_escape_string($_POST['documento']);
    $nombre_ingresado = trim(strtolower($conn->real_escape_string($_POST['nombre'])));

    // Buscar por documento (único)
    $sql = "SELECT * FROM clientes WHERE numero_documento = '$documento' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        // Comprobar que el nombre ingresado esté contenido en el nombre registrado (ignorando mayúsculas/minúsculas)
        $nombre_registrado = strtolower($usuario['nombre']);
        if ($nombre_ingresado === '' || strpos($nombre_registrado, $nombre_ingresado) !== false) {
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['cliente_id'] = $usuario['id'];
            $_SESSION['cliente_nombre'] = $usuario['nombre'];
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['no_coincide_nombre'] = true;
        }
    } else {
        $_SESSION['no_registrado'] = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión | Tienda Lilivan</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="otros/css/ingresar.css">
    
</head>
<body>

<?php if (isset($_SESSION['no_registrado'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Usuario no registrado',
    text: 'Los datos ingresados no están registrados. Por favor, regístrate o verifica tus datos.',
    confirmButtonText: 'Aceptar'
});
</script>
<?php unset($_SESSION['no_registrado']); endif; ?>

<?php if (isset($_SESSION['no_coincide_nombre'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Nombre incorrecto',
    text: 'El nombre ingresado no coincide con el registrado para este documento.',
    confirmButtonText: 'Aceptar'
});
</script>
<?php unset($_SESSION['no_coincide_nombre']); endif; ?>

<div class="login-box">
    <span class="icon-lilivan"><i class="fas fa-wine-glass-alt"></i></span>
    <div class="login-title">Iniciar Sesión</div>
    <form method="post" action="">
        <div class="user-box">
            <input required name="nombre" type="text" autocomplete="off" placeholder=" " />
            <label><i class="fas fa-user"></i> Nombre</label>
        </div>
        <div class="user-box">
            <input required name="documento" type="text" autocomplete="off" placeholder=" " />
            <label><i class="fas fa-id-card"></i> Número de Documento</label>
        </div>
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Ingresar
        </button>
    </form>
    <div class="divider">
        ¿No tienes una cuenta? <a href="registrar_form.php">Registrarse</a>
    </div>
    <br>
    <div style="text-align:center; margin-top:12px;">
        <a href="index.php" class="a-volver-inicio"><i class="fas fa-arrow-left"></i> Volver al inicio</a>
    </div>
</div>

</body>
</html>