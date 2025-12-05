<?php
include "../config/db.php";
session_start();

// Protección de acceso
if (!isset($_SESSION["admin"])) {
    header("Location: /mi_tienda/admin/login.php");
    exit;
}

$categorias = ["general", "celulares", "teclados", "mouse", "audifonos", "accesorios"];

$msjOk = $msjError = "";

// Procesar envío
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre      = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio      = (int)$_POST["precio"];
    $stock       = (int)$_POST["stock"];
    $categoria   = $_POST["categoria"];
    $imagen      = trim($_POST["imagen"]);

    // Validación
    if ($nombre === "" || $descripcion === "" || $precio <= 0 || $stock < 0 || $imagen === "") {
        $msjError = "Por favor completa todos los campos correctamente.";
    } else {
        $nombreEsc = $conexion->real_escape_string($nombre);
        $descripcionEsc = $conexion->real_escape_string($descripcion);
        $categoriaEsc = $conexion->real_escape_string($categoria);
        $imagenEsc = $conexion->real_escape_string($imagen);

        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, stock, categoria)
                VALUES ('$nombreEsc', '$descripcionEsc', $precio, '$imagenEsc', $stock, '$categoriaEsc')";

        if ($conexion->query($sql)) {
            $msjOk = "Producto creado correctamente.";
        } else {
            $msjError = "Error al crear producto.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear producto | Admin</title>
    <link rel="stylesheet" href="/mi_tienda/admin/admin.css">
</head>
<body>

<!-- HEADER ADMIN -->
<header class="admin-header">
    <h1>Crear producto</h1>
    <nav>
        <a href="/mi_tienda/admin/dashboard.php">Dashboard</a>
        <a href="/mi_tienda/admin/index.php">Productos</a>
        <a href="/mi_tienda/admin/logout.php" class="logout">Cerrar sesión</a>
    </nav>
</header>

<!-- TARJETA DEL FORMULARIO -->
<div class="form-card">
    <h1>Nuevo producto</h1>

    <?php if ($msjOk): ?>
        <div class="admin-msj-ok"><?= $msjOk ?></div>
    <?php endif; ?>

    <?php if ($msjError): ?>
        <div class="admin-msj-error"><?= $msjError ?></div>
    <?php endif; ?>

    <form method="POST">

    <div class="form-group">
        <label>Nombre del producto</label>
        <input type="text" name="nombre" required>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" required></textarea>
    </div>

    <div class="form-group">
        <label>Precio (CLP)</label>
        <input type="number" name="precio" required min="1">
    </div>

    <div class="form-group">
        <label>Stock</label>
        <input type="number" name="stock" required min="0">
    </div>

    <div class="form-group">
        <label>Categoría</label>
        <select name="categoria" required>
            <option value="">Selecciona...</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c ?>"><?= ucfirst($c) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- NUEVO: Campo imagen + preview -->
    <div class="form-group">
        <label>Nombre del archivo de imagen</label>
        <input 
            type="text" 
            name="imagen" 
            id="imagen_input"
            placeholder="ej: producto1.png"
            oninput="previewImage()" 
            required
        >
    </div>

    <!-- PREVIEW -->
    <div class="img-preview-box">
        <img id="img_preview" style="display:none;">
    </div>

    <div class="form-actions">
        <a href="/mi_tienda/admin/index.php" class="btn-secundario">Volver</a>
        <button type="submit" class="btn-primario">Guardar</button>
    </div>

</form>
<script>
function previewImage() {
    const fileName = document.getElementById("imagen_input").value.trim();
    const preview = document.getElementById("img_preview");

    // Si no escribe nada, ocultar imagen
    if (fileName === "") {
        preview.src = "";
        preview.style.display = "none";
        return;
    }

    // Mostrar imagen desde /img/
    preview.src = "/mi_tienda/img/" + fileName;
    preview.style.display = "block";
}
</script>


</body>
</html>
