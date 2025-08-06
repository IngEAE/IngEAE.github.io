<?php
// filepath: c:\xampp\htdocs\Lilivan2\productos.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';


// Obtener productos con categorías
$sql = "SELECT p.id, p.nombre, p.descripcion, p.descripcion_detallada, p.precio, p.stock, p.imagen_url, c.nombre AS categoria_nombre
        FROM productos p
        LEFT JOIN catalogo c ON p.id_categoria = c.id";
$result = $conn->query($sql);
$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

// Obtener todas las categorías para filtros
$categorias_result = $conn->query("SELECT nombre FROM catalogo");
$categorias = [];
while ($cat = $categorias_result->fetch_assoc()) {
    $categorias[] = $cat['nombre'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos | Lilivan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Roboto&family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="otros/css/productos.css">
  
</head>
<body>

<main>
  <h1>Descubre Nuestros Tesoros</h1>
  <p class="subtitulo">Explora nuestra exclusiva selección de vinos, licores y cervezas artesanales.</p>

  <div class="filtros">
    <button class="active" onclick="filtrar('Todos')">Todos</button>
    <?php foreach ($categorias as $cat): ?>
      <button onclick="filtrar('<?= htmlspecialchars($cat) ?>')"><?= htmlspecialchars($cat) ?></button>
    <?php endforeach; ?>
  </div>

  <div class="catalogo" id="catalogo">
    <?php foreach ($productos as $p): ?>
      <div class="producto" data-categoria="<?= htmlspecialchars($p['categoria_nombre']) ?>">
        <span class="categoria-label"><?= htmlspecialchars($p['categoria_nombre']) ?></span>
        <img src="otros/img/<?= htmlspecialchars($p['imagen_url']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
        <div class="contenido">
          <h2><?= htmlspecialchars($p['nombre']) ?></h2>
          <p class="descripcion"><?= htmlspecialchars($p['descripcion']) ?></p>
          <div class="precio-stock">
            <span class="precio"><strong>Precio:</strong> $<?= number_format($p['precio'], 0, '', '.') ?> COP</span>
            <span class="stock">Disponible <?= (int)$p['stock'] ?> unidades</span>
          </div>
          <div class="botones">
            <button class="boton btn-info" type="button"
  onclick="mostrarModal(
    `<?= addslashes($p['id']) ?>`,
    `<?= addslashes($p['nombre']) ?>`,
    `<?= $p['precio'] ?>`,
    `<?= $p['imagen_url'] ?>`,
    `<?= addslashes($p['descripcion_detallada'] ?? $p['descripcion']) ?>`,
    `<?= $p['categoria_nombre'] ?>`,
    `<?= (int)$p['stock'] ?>`
  )"
>
  <i class="fas fa-info-circle"></i> Más Info
</button>
            <form class="carrito-form" method="post" action="agregar_al_carrito.php" style="width:100%;margin-top:10px;">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <input type="hidden" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>">
              <input type="hidden" name="precio" value="<?= $p['precio'] ?>">
              <input type="hidden" name="imagen" value="<?= htmlspecialchars($p['imagen_url']) ?>">
              <input type="hidden" name="cantidad" value="1">
              <button type="submit" class="boton btn-carrito" style="width:100%;">
                <i class="fas fa-cart-plus"></i>Agregar al carrito
              </button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<!-- MODAL SOLO PARA INFO DE PRODUCTO Y AGREGAR AL CARRITO -->
<div id="infoModal" class="modal">
  <div class="modal-content">
    <button class="close" onclick="cerrarModal()">&times;</button>
    <h2 id="modalNombre" class="modal-title"></h2>
    <div class="modal-body">
      <div class="modal-img">
        <img id="modalImagen" src="" alt="">
      </div>
      <div class="modal-details">
        <p id="modalCategoria" class="categoria-modal"></p>
        <p id="modalPrecio" class="precio-modal"></p>
        <p id="modalDescripcion" class="descripcion-modal"></p>
        <form id="modalCarritoForm" action="agregar_al_carrito.php" method="post">
          <input type="hidden" name="id" id="modalId">
          <input type="hidden" name="nombre" id="modalNombreInput">
          <input type="hidden" name="precio" id="modalPrecioInput">
          <input type="hidden" name="imagen" id="modalImagenInput">
          <div class="cantidad-control">
            <label for="modalCantidad">Cantidad:</label>
            <button type="button" onclick="cambiarCantidad(-1)">−</button>
            <input type="number" id="modalCantidad" name="cantidad" value="1" min="1">
            <button type="button" onclick="cambiarCantidad(1)">+</button>
          </div>
          <p style="margin-top: 1rem;">Total: <span id="modalTotal" class="precio-modal"></span></p>
            <button type="submit" class="boton btn-carrito" style="width:100%;margin-top:10px;">
              <i class="fas fa-cart-plus"></i>Agregar al carrito
            </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- MODAL CONFIRMACIÓN AGREGADO -->
<div id="modalAgregado" class="modal">
  <div class="modal-content" style="max-width:350px;text-align:center;">
    <button class="close" onclick="cerrarModalAgregado()">&times;</button>
    <div style="font-size:2.5em;color:#3b82f6;margin-bottom:10px;"><i class="fas fa-check-circle"></i></div>
    <div id="mensajeAgregado" style="font-size:1.1em;margin-bottom:10px;">¡Producto agregado al carrito!</div>
    <button onclick="cerrarModalAgregado()" class="boton btn-carrito" style="width:100%;margin-top:10px;">Seguir comprando</button>
    <button onclick="window.location.href='carrito.php'" class="boton btn-info" style="width:100%;margin-top:10px;">Ver carrito</button>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  window.usuarioLogueado = <?php echo isset($_SESSION['cliente_id']) ? 'true' : 'false'; ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="./otros/js/productos.js"></script>

</body>
</html>