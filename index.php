<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <title>Lilivan | Estanco Premium</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="otros/css/Index.css">
   
</head>
<body>
    <!-- Barra de navegación moderna -->
<header class="navbar">
  <div class="navbar-container nav-3col">
    <div class="nav-left">
      <div class="logo">
        <i class="fas fa-wine-glass-alt"></i> Lilivan
      </div>
    </div>
    <div class="nav-center">
      <nav class="nav-menu">
        <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Inicio</a>
        <a href="#productos" class="nav-link<?= isset($_GET['productos']) ? ' active' : '' ?>">Productos</a>
        <a href="#ubicacion" class="nav-link">Ubicación</a>
        <a href="#testimonios" class="nav-link">Testimonios</a>
        <a href="carrito.php" class="btn-nav-carrito"><i class="fas fa-shopping-basket"></i> Carrito</a>
        <!-- El botón de carrito ahora siempre lleva a carrito.php, sin forzar login -->
      </nav>
    </div>
    <div class="nav-right">
      <?php if (isset($_SESSION['cliente_id'])): ?>
        <span class="nav-user"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['cliente_nombre']) ?></span>
        <a href="cerrar_sesi.php" class="nav-link logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
      <?php else: ?>
        <div class="admin-dropdown">
          <button id="adminBtn" class="nav-link" type="button">
            <i class="fas fa-user-shield"></i> <i class="fas fa-caret-down"></i>
          </button>
          <div id="adminMenu" class="dropdown-menu">
            <a href="ini_sesi_form.php">Iniciar sesión</a>
            <a href="registrar_form.php">Registrarme</a>
            <a href="admin/login_form.php">Panel Admin</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- Carrusel tipo hero visual y animado -->
<div class="swiper hero-swiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide hero-slide" style="background-image:url('./otros/img/hero.jpg');">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Bienvenido a <span>Lilivan</span></h1>
                <p>Estanco Premium desde 2001. La mejor selección en gaseosas, licores y bebidas premium.</p>
            </div>
        </div>
        <div class="swiper-slide hero-slide" style="background-image:url('./otros/img/estanteria.webp');">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Calidad y Servicio</h1>
                <p>Más de 24 años de experiencia ofreciendo atención personalizada y profesional.</p>
            </div>
        </div>
        <div class="swiper-slide hero-slide" style="background-image:url('./otros/img/productos.jpg');">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>¡Explora nuestro catálogo!</h1>
                <p>Vinos, cervezas, licores y mucho más. Calidad excepcional para cada ocasión.</p>
            </div>
        </div>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

<!-- Sobre Lilivan mejorado y visual -->
<section class="sobre" id="sobre" style="background:linear-gradient(135deg,#1e293b 80%,#334155 100%);padding:70px 0 40px;display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:60px;border-radius:0 0 40px 40px;box-shadow:0 8px 32px #2563eb40;">
    <div class="sobre-texto" style="flex:1;max-width:540px;background:rgba(30,41,59,0.93);border-radius:18px;padding:38px 28px;box-shadow:0 2px 12px #33415540;">
        <h2 style="font-family:'Playfair Display',serif;font-size:34px;color:#3b82f6;margin-bottom:18px;letter-spacing:1px;">Sobre Lilivan</h2>
        <p style="color:#cbd5e1;font-size:17px;line-height:1.7;margin-bottom:18px;">
            Desde 2001, Lilivan es tu estanco premium en Neiva. Ofrecemos bebidas, licores y atención personalizada. Trabajamos con marcas reconocidas y brindamos una experiencia única para nuestros clientes.
        </p>
        <div class="sobre-cards" style="display:flex;gap:18px;margin-top:18px;">
            <div class="sobre-card" style="background:#334155;border-radius:12px;padding:18px 22px;color:#fff;box-shadow:0 2px 12px #2563eb40;font-size:15px;display:flex;align-items:center;gap:12px;">
                <i class="fas fa-award" style="font-size:22px;color:#3b82f6;"></i> Calidad Premium
            </div>
            <div class="sobre-card" style="background:#334155;border-radius:12px;padding:18px 22px;color:#fff;box-shadow:0 2px 12px #2563eb40;font-size:15px;display:flex;align-items:center;gap:12px;">
                <i class="fas fa-clock" style="font-size:22px;color:#3b82f6;"></i> 24+ años de experiencia
            </div>
            <div class="sobre-card" style="background:#334155;border-radius:12px;padding:18px 22px;color:#fff;box-shadow:0 2px 12px #2563eb40;font-size:15px;display:flex;align-items:center;gap:12px;">
                <i class="fas fa-star" style="font-size:22px;color:#3b82f6;"></i> Servicio excepcional
            </div>
        </div>
    </div>
    <img src="./otros/img/estanteria.webp" alt="Lilivan tienda" style="border-radius:18px;max-width:420px;width:100%;box-shadow:0 2px 12px #33415540;">
</section>

<!-- Productos integrados -->
<section id="productos">
    <?php include 'productos.php'; ?>
</section>

<!-- Testimonios de clientes -->
<section class="testimonios" id="testimonios" style="background:linear-gradient(135deg,#1e293b 80%,#334155 100%);padding:60px 0 40px;">
    <div style="max-width:900px;margin:0 auto;text-align:center;">
        <h2 style="font-family:'Playfair Display',serif;font-size:32px;color:#3b82f6;margin-bottom:30px;">Lo que dicen nuestros clientes</h2>
        <div id="carrusel-comentarios" style="margin:0 auto;">
            <div class="swiper comentarios-swiper">
                <div class="swiper-wrapper" id="comentariosWrapper">
                    <!-- Comentarios se insertan aquí vía JS -->
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
</section>
<!-- Aquí termina la sección de testimonios -->

<!-- Botón flotante para agregar comentario -->
<button id="btnComentar" class="btn-comentario-flotante">
    <i class="fas fa-comment-dots"></i>
</button>

<!-- Formulario flotante -->
<div id="formComentario" class="form-comentario-overlay">
    <form id="comentarioForm" class="form-comentario">
        <button type="button" id="cerrarForm" class="cerrar-form"><i class="fas fa-times"></i></button>
        <h3>Nuevo comentario</h3>
        <input type="text" name="nombre" placeholder="Tu nombre" required>
        <textarea name="comentario" placeholder="Tu comentario" required></textarea>
        <button type="submit">Enviar</button>
    </form>
</div>

<!-- Ubicación y contacto -->
<section class="ubicacion-contacto" id="ubicacion">
    <div class="ubicacion-mapa">
        <iframe src="https://www.google.com/maps?q=Calle+37+6-42,+Neiva,+Huila,+Colombia&output=embed"
            width="100%" height="320" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="ubicacion-info">
        <h3>¿Dónde estamos?</h3>
        <p>Visítanos en nuestra tienda física o contáctanos para pedidos y consultas.</p>
        <div class="contacto-dato"><i class="fas fa-map-marker-alt"></i> Calle 37#6-42, Granjas (Neiva-Huila)</div>
        <div class="contacto-dato"><i class="fas fa-phone"></i> <a href="tel:3163007815" style="color:#60a5fa;">3163007815</a></div>
        <div class="contacto-dato"><i class="fab fa-whatsapp"></i> <a href="https://wa.me/573163007815" target="_blank" style="color:#60a5fa;">WhatsApp</a></div>
        <div class="contacto-dato"><i class="fas fa-envelope"></i> <a href="mailto:hercoleon@gmail.com" style="color:#60a5fa;">hercoleon@gmail.com</a></div>
    </div>
</section>
<br><br>
<!-- Footer renovado -->
<footer>
    <div>
        <h4><i class="fas fa-wine-glass-alt"></i> Lilivan</h4>
        <p>Tu estanco de confianza desde 2001. Calidad, variedad y atención personalizada.</p>
    </div>
    <div>
        <h4>Enlaces Rápidos</h4>
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#productos">Productos</a></li>
            <li><a href="#ubicacion">Ubicación</a></li>
            <li><a href="ini_sesi_form.php">Iniciar sesión</a></li>
        </ul>
    </div>
    <div>
        <h4>Categorías</h4>
        <ul>
            <li>Gaseosas</li>
            <li>Cervezas</li>
            <li>Licores</li>
            <li>Cigarrillo</li>
            <li>Agua</li>
        </ul>
    </div>
    <div>
        <h4>Contacto</h4>
        <p><i class="fas fa-map-marker-alt"></i> Calle 37#6-42, Granjas (Neiva-Huila) </p>
        <p><i class="fas fa-phone"></i> 3163007815 </p>
        <p><i class="fas fa-envelope"></i> hercoleon@gmail.com </p>
        <div class="horario"><strong>Horario:</strong><br> Lunes a Jueves, 9:00 am – 10:00 pm <br> Viernes a Domingo, 9:00 am – 2:00 am </div>
    </div>
</footer>



<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="./otros/js/index.js"></script>
<script src="./otros/js/comentario.js"></script>

</body>
</html>