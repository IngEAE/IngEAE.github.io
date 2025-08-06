
# Lilivan | Estanco Premium

Lilivan es una plataforma web para gestionar y vender productos de un estanco premium. Incluye funciones para que los clientes naveguen, seleccionen productos, agreguen al carrito y completen su compra, además de un panel de administración para gestionar productos, clientes y usuarios del sistema.

---

## 🧩 Características principales

- 🛍️ Carrito de compras funcional
- 🔐 Registro e inicio de sesión de clientes
- 🛠️ Panel de administración con control de:
  - Productos
  - Categorías
  - Clientes
  - Usuarios
- 🗃️ Base de datos SQL incluida (`lilivan-v3.sql`)
- 💬 Sistema de comentarios por producto

---

## 📁 Estructura del proyecto

```
Lilivan-v6/
├── index.php                   # Página principal
├── productos.php               # Catálogo de productos
├── carrito.php                 # Página del carrito
├── registrar_form.php         # Registro de clientes
├── ini_sesi_form.php          # Inicio de sesión
├── admin/                     # Panel de administración
│   ├── dashboard.php
│   ├── productos/
│   ├── clientes/
│   ├── usuarios/
│   └── catalogos/
├── conexion.php               # Conexión a la base de datos
├── lilivan-v3.sql             # Script de base de datos
```

---

## 🛠️ Instalación

### 1. Requisitos

- Servidor local (como XAMPP, WAMP o Laragon)
- PHP 7.x o superior
- MySQL

### 2. Pasos

1. Clona o descomprime este repositorio dentro de la carpeta `htdocs` o similar:
   ```bash
   /xampp/htdocs/Lilivan-v6
   ```

2. Crea una base de datos en **phpMyAdmin** llamada:
   ```
   lilivan
   ```

3. Importa el archivo SQL:
   ```
   lilivan-v3.sql
   ```

4. Configura las credenciales en `conexion.php` si es necesario:
   ```php
   $conn = new mysqli("localhost", "root", "", "lilivan");
   ```

5. Abre en tu navegador:
   ```
   http://localhost/Lilivan-v6/
   ```

---

## 👤 Usuarios de prueba

### Cliente
- Usuario: `cliente@ejemplo.com`
- Contraseña: `123456`

### Administrador
- Usuario: `admin@admin.com`
- Contraseña: `admin`

*(Los valores pueden variar según los datos ingresados en la base)*

---

## 📌 Notas

- Los archivos del carrito y comentarios utilizan Ajax para actualización en tiempo real.
- Puedes modificar estilos, textos y funciones según tu necesidad.
- Recomendado activar logs y errores en desarrollo para depurar.

---

## 🧑‍💻 Autor

Desarrollado por **Edwin Alejandro Esquivel Bahamón**  
Contacto: [tu correo o portafolio aquí]

---
