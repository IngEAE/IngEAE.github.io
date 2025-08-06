<?php
session_start();
include("conexion.php");

// Registro si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['fullname']);
    $numero_documento = $conn->real_escape_string($_POST['documento']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $correo_electronico = $conn->real_escape_string($_POST['email']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $observaciones = $conn->real_escape_string($_POST['observaciones']);

    // Verifica duplicado
    $sql_check = "SELECT id FROM clientes WHERE numero_documento = '$numero_documento' OR correo_electronico = '$correo_electronico' LIMIT 1";
    $result_check = $conn->query($sql_check);

    if ($result_check && $result_check->num_rows > 0) {
        $_SESSION['duplicado'] = true;
    } else {
        // Insertar cliente
        $sql = "INSERT INTO clientes (nombre, numero_documento, telefono, correo_electronico, direccion, observaciones)
                VALUES ('$nombre', '$numero_documento', '$telefono', '$correo_electronico', '$direccion', '$observaciones')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['registro_exitoso'] = true;
            header("Location: registrar_form.php");
            exit;
        } else {
            $_SESSION['error'] = $conn->error;
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarme | Tienda Lilivan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="otros/css/registrar.css">
    
</head>
<body>

<?php if (isset($_SESSION['registro_exitoso'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Registro exitoso!',
    text: 'El cliente ha sido registrado correctamente.',
    confirmButtonText: 'Aceptar'
}).then(() => {
    window.location.href = 'index.php';
});
</script>
<?php unset($_SESSION['registro_exitoso']); endif; ?>

<?php if (isset($_SESSION['duplicado'])): ?>
<script>
Swal.fire({
    icon: 'warning',
    title: '¡Registro duplicado!',
    text: 'El número de documento o correo electrónico ya está registrado.',
    confirmButtonText: 'Aceptar'
});
</script>
<?php unset($_SESSION['duplicado']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: '¡Error!',
    text: '<?php echo $_SESSION["error"]; ?>',
    confirmButtonText: 'Aceptar'
});
</script>
<?php unset($_SESSION['error']); endif; ?>

<div class="container-main">
    <div class="form-box">
        <span class="icon-lilivan"><i class="fas fa-wine-glass-alt"></i></span>
        <div class="form-title">
            Registrarme en Lilivan
        </div>
        <form method="post" action="" id="form-registro">
            <div class="form-grid">
                <div>
                    <label for="fullname"><i class="fas fa-user"></i> Nombre completo</label>
                    <input type="text" id="fullname" name="fullname" required placeholder="Ej: Juan Pérez" />
                </div>
                <div>
                    <label for="documento"><i class="fas fa-id-card"></i> Número de documento</label>
                    <input type="text" id="documento" name="documento" required placeholder="Ej: 123456789" />
                </div>
                <div>
                    <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" required placeholder="Ej: 3163007815" />
                </div>
                <div>
                    <label for="email"><i class="fas fa-envelope"></i> Correo electrónico</label>
                    <input type="email" id="email" name="email" required placeholder="Ej: correo@dominio.com" />
                </div>
                <div>
                    <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección</label>
                    <input type="text" id="direccion" name="direccion" required placeholder="Ej: Calle 37#6-42" />
                </div>
                <div>
                    <label for="observaciones"><i class="fas fa-comment-dots"></i> Observaciones</label>
                    <textarea id="observaciones" name="observaciones" rows="3" placeholder="Observaciones generales fuera de su casa"></textarea>
                </div>
            </div>
            <div style="margin-top: 24px;">
                <button class="btn-submit" type="submit">
                    <i class="fas fa-user-plus"></i> Registrarme
                </button>
            </div>
        </form>
        <div class="divider">
            <a href="ini_sesi_form.php"><i class="fas fa-sign-in-alt"></i> ¿Ya tienes una cuenta? Inicia sesión</a>
        </div>
        <br>
        <div style="text-align:center; margin-top:12px;">
          <a href="index.php" class="a-volver-inicio"><i class="fas fa-arrow-left"></i> Volver al inicio</a>
        </div>
    </div>
</div>

<!-- SwiperJS Carrusel -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="./otros/js/registrar_form.js"></script>

</body>
</html>