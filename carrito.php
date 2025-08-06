<?php
session_start();
include 'conexion.php';

$cliente_id = $_SESSION['cliente_id'] ?? null;

// SIEMPRE carrito en sesión
if (!isset($_SESSION['carrito_tmp'])) {
    $_SESSION['carrito_tmp'] = [];
}

// --- PROCESAR ACCIONES DE CANTIDAD Y ELIMINAR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'], $_POST['index'])) {
    $idx = (int)$_POST['index'];
    if (isset($_SESSION['carrito_tmp'][$idx])) {
        if ($_POST['accion'] === 'sumar') {
            $_SESSION['carrito_tmp'][$idx]['cantidad']++;
        } elseif ($_POST['accion'] === 'restar') {
            $_SESSION['carrito_tmp'][$idx]['cantidad']--;
            if ($_SESSION['carrito_tmp'][$idx]['cantidad'] <= 0) {
                array_splice($_SESSION['carrito_tmp'], $idx, 1);
            }
        } elseif ($_POST['accion'] === 'eliminar') {
            array_splice($_SESSION['carrito_tmp'], $idx, 1);
        }
    }
    // Refresca para evitar re-envío de formulario
    header("Location: carrito.php");
    exit;
}

$productos = $_SESSION['carrito_tmp'];
$total = 0;
foreach ($productos as $row) {
    $precio = (int)str_replace('.', '', $row['precio']); // Elimina todos los puntos
    $total += $precio * $row['cantidad'];
}

// Construir el mensaje de WhatsApp
if ($cliente_id) {
    // Obtener datos del cliente
    $stmt = $conn->prepare("SELECT nombre, direccion, telefono FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $stmt->bind_result($cli_nombre, $cli_direccion, $cli_telefono);
    $stmt->fetch();
    $stmt->close();
    $mensaje = "Hola, quiero confirmar mi compra.\n";
    $mensaje .= "Nombre: $cli_nombre\n";
    $mensaje .= "Dirección: $cli_direccion\n";
    $mensaje .= "Teléfono: $cli_telefono\n";
    $mensaje .= "\nProductos:\n";
    foreach ($productos as $prod) {
        $mensaje .= "- " . $prod['cantidad'] . " x " . $prod['nombre'] . "\n";
    }
    $mensaje .= "\nTotal a pagar: $" . number_format($total, 0, ',', '.') . " pesos.";
    $mensaje = urlencode($mensaje);
} else {
    $mensaje = "Hola, quiero confirmar mi compra de los siguientes productos:%0A";
    foreach ($productos as $prod) {
        $mensaje .= "- " . $prod['cantidad'] . " x " . $prod['nombre'] . "%0A";
    }
    $mensaje .= "%0AEl total a pagar sería de *$" . number_format($total, 0, ',', '.') . "* pesos.";
    $mensaje = urlencode($mensaje);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras | Lilivan</title>
    <link rel="stylesheet" href="otros/css/carrito.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<div class="main-container">
    <!-- Columna izquierda: Productos -->
    <div class="carrito-col">
        <div class="carrito-header">
            <a href="index.php" class="btn-volver-productos" title="Volver a productos">
                <i class="fas fa-arrow-left"></i>
            </a>
            Carro de compras <span style="color:#bfcfff;font-size:1rem;">(<?= count($productos) ?>)</span>
        </div>
        <?php if (empty($productos)): ?>
            <p style="color:#bfcfff;">No tienes productos en el carrito.</p>
        <?php else: ?>
            <?php foreach ($productos as $idx => $prod): ?>
                <?php
                    // Si el precio es menor a 10000, probablemente está mal guardado
                    $precio = (int)str_replace('.', '', $prod['precio']);
                    if ($precio < 10000 && strlen($prod['precio']) >= 5) {
                        // Si el precio original tiene punto y es menor a 10000, multiplica por 10
                        $precio = $precio * 10;
                    }
                ?>
                <div class="producto">
                    <img src="otros/img/<?= htmlspecialchars($prod['imagen_url']) ?>" alt="Producto"
                         onerror="this.onerror=null;this.src='https://via.placeholder.com/90x90?text=Sin+Imagen';">
                    <div class="producto-info">
                        <h4><?= htmlspecialchars($prod['nombre']) ?></h4>
                        <p><?= htmlspecialchars($prod['descripcion']) ?></p>
                        <div class="producto-total">
                            Total producto: <span class="producto-precio">$<?= number_format($precio * $prod['cantidad'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <div class="acciones">
                        <div class="cantidad-control">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="index" value="<?= $idx ?>">
                                <input type="hidden" name="accion" value="restar">
                                <button type="submit" title="Restar unidad">-</button>
                            </form>
                            <span style="color:#fff;font-weight:bold;"><?= $prod['cantidad'] ?></span>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="index" value="<?= $idx ?>">
                                <input type="hidden" name="accion" value="sumar">
                                <button type="submit" title="Sumar unidad">+</button>
                            </form>
                        </div>
                        <form method="POST" class="form-eliminar" style="display:inline;width:100%;">
                            <input type="hidden" name="index" value="<?= $idx ?>">
                            <input type="hidden" name="accion" value="eliminar">
                            <button type="submit" class="eliminar-btn" title="Eliminar producto">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($productos) && !$cliente_id): ?>
        <!-- Formulario de detalles de facturación centrado SOLO si NO está logueado -->
        <div style="display:flex;justify-content:center;width:100%;margin-top:32px;">
            <form id="form-facturacion" style="background:#101c36;padding:22px 18px;border-radius:10px;box-shadow:0 2px 8px rgba(25,118,210,0.10);color:#fff;max-width:500px;width:100%;">
                <h3 style="margin-top:0;margin-bottom:18px;font-size:1.15rem;color:#63a4ff;">Detalles de facturación</h3>
                <div style="margin-bottom:12px;">
                    <label for="nombre" style="display:block;margin-bottom:4px;">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" required style="width:100%;padding:7px 9px;border-radius:6px;border:1px solid #23305c;background:#192447;color:#fff;">
                </div>
                <div style="margin-bottom:12px;">
                    <label for="direccion" style="display:block;margin-bottom:4px;">Dirección</label>
                    <input type="text" id="direccion" name="direccion" required style="width:100%;padding:7px 9px;border-radius:6px;border:1px solid #23305c;background:#192447;color:#fff;">
                </div>
                <div style="margin-bottom:12px;">
                    <label for="telefono" style="display:block;margin-bottom:4px;">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" required style="width:100%;padding:7px 9px;border-radius:6px;border:1px solid #23305c;background:#192447;color:#fff;">
                </div>
                <div style="margin-bottom:16px;">
                    <label for="notas" style="display:block;margin-bottom:4px;">Notas adicionales (opcional)</label>
                    <textarea id="notas" name="notas" rows="2" style="width:100%;padding:7px 9px;border-radius:6px;border:1px solid #23305c;background:#192447;color:#fff;"></textarea>
                </div>
                <button type="submit" style="width:100%;padding:12px 0;background:#1976d2;color:#fff;font-size:1.08rem;font-weight:bold;border:none;border-radius:6px;cursor:pointer;transition:background 0.2s;">Finalizar compra por WhatsApp</button>
            </form>
        </div>
        <script>
        document.getElementById('form-facturacion').addEventListener('submit', function(e) {
            e.preventDefault();
            var nombre = document.getElementById('nombre').value.trim();
            var direccion = document.getElementById('direccion').value.trim();
            var telefono = document.getElementById('telefono').value.trim();
            var notas = document.getElementById('notas').value.trim();
            var productos = <?php echo json_encode($productos); ?>;
            var total = '<?= number_format($total, 0, ',', '.') ?>';
            var mensaje = 'Hola, quiero confirmar mi compra.\n';
            mensaje += 'Nombre: ' + nombre + '\n';
            mensaje += 'Dirección: ' + direccion + '\n';
            mensaje += 'Teléfono: ' + telefono + '\n';
            if(notas) mensaje += 'Notas: ' + notas + '\n';
            mensaje += '\nProductos:\n';
            productos.forEach(function(prod) {
                mensaje += '- ' + prod.cantidad + ' x ' + prod.nombre + '\n';
            });
            mensaje += '\nTotal a pagar: $' + total + ' pesos.';
            var url = 'https://wa.me/573001234567?text=' + encodeURIComponent(mensaje);
            window.open(url, '_blank');
        });
        </script>
        <?php endif; ?>
    </div>

    <!-- Columna derecha: Resumen -->
    <div class="resumen-col">
        <!-- Mensaje casi transparente, no editable -->
        <div class="editable-info" style="opacity:0.6;pointer-events:none;">
            Recuerda que el pedido será confirmado por WhatsApp. Si tienes alguna indicación especial, escríbela en el chat al continuar.
        </div>
        <div class="resumen-title">Resumen de tu compra</div>
        <div class="resumen-line">
            <span>Sub total</span>
            <span>$<?= number_format($total, 0, ',', '.') ?></span>
        </div>
        <div class="resumen-total">
            Total a pagar<br>
            $<?= number_format($total, 0, ',', '.') ?>
        </div>
        <div class="resumen-info">
            Verifica bien los productos y cantidad seleccionados, si estas seguro de esto continua con la compra al presionar el siguiente boton.
        </div>

        <?php if ($cliente_id): ?>
        <a href="https://wa.me/573183577316?text=<?= $mensaje ?>" target="_blank" class="resumen-btn">
            Continuar con la compra
        </a>
        <?php endif; ?>
        <div class="resumen-info">
            Precios y opciones de entrega basados en tu ubicación actual.
        </div>
        <div class="resumen-box">
            <strong>Compra segura</strong><br>
            Tus datos personales se mantienen bajo estricta confidencialidad y están protegidos.
        </div>
        <div class="resumen-box">
            <strong>Medida de seguridad</strong><br>
            Para realizar el envio de su pedido debera abonar la mitad o pagar toda la compra por adelantado. 
        </div>
        <div class="resumen-box">
            <strong>¿Necesitas ayuda?</strong><br>
            Si necesitas ayuda para completar tu compra llámanos al <b>316 300 7815</b>
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SwiperJS Carrusel -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="./otros/js/carrito.js"></script>

</body>
</html>
