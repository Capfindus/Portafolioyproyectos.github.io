<?php
include "../config/db.php";
session_start();

// Protección de acceso
if (!isset($_SESSION["admin"])) {
    header("Location: /mi_tienda/admin/login.php");
    exit;
}

// Validar ID
if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET["id"];

$categorias = ["general", "celulares", "teclados", "mouse", "audifonos", "accesorios"];

// Obtener datos del producto
$sql = "SELECT * FROM productos WHERE id = $id";
$producto = $conexion->query($sql)->fetch_assoc();

if (!$producto) {
    header("Location: index.php");
    exit;
}

$msjOk = $msjError = "";

// Procesar envío
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre      = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio      = (int)$_POST["precio"];
    $stock       = (int)$_POST["stock"];
    $imagen      = trim($_POST["imagen"]);
    $categoria   = $_POST["categoria"];

    if ($nombre === "" || $descripcion === "" || $precio <= 0 || $stock < 0 || $imagen === "") {
        $msjError = "Completa todos los campos correctamente.";
    } else {

        // Escapar
        $nombreEsc = $conexion->real_escape_string($nombre);
        $descripcionEsc = $conexion->real_escape_string($descripcion);
        $imagenEsc = $conexion->real_escape_string($imagen);
        $categoriaEsc = $conexion->real_escape_string($categoria);

        $updateSQL = "
            UPDATE productos SET
                nombre='$nombreEsc',
                descripcion='$descripcionEsc',
                precio=$precio,
                stock=$stock,
                imagen='$imagenEsc',
                categoria='$categoriaEsc'
            WHERE id=$id
        ";

        if ($conexion->query($updateSQL)) {
            $msjOk = "Cambios guardados correctamente.";
        } else {
            $msjError = "Error al actualizar el producto.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto | Admin</title>
    <link rel="stylesheet" href="/mi_tienda/admin/admin.css">
</head>
<body>

<!-- HEADER ADMIN -->
<header class="admin-header">
    <h1>Editar producto</h1>
    <nav>
        <a href="/mi_tienda/admin/dashboard.php">Dashboard</a>
        <a href="/mi_tienda/admin/index.php">Productos</a>
        <a href="/mi_tienda/admin/logout.php" class="logout">Cerrar sesión</a>
    </nav>
</header>

<!-- FORMULARIO -->
<div class="form-card">
    <h1>Editar Producto</h1>

    <?php if ($msjOk): ?>
        <div class="admin-msj-ok"><?= $msjOk ?></div>
    <?php endif; ?>

    <?php if ($msjError): ?>
        <div class="admin-msj-error"><?= $msjError ?></div>
    <?php endif; ?>

    <form method="POST">

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
    </div>

    <div class="form-group">
        <label>Precio (CLP)</label>
        <input type="number" name="precio" value="<?= $producto['precio'] ?>" required min="1">
    </div>

    <div class="form-group">
        <label>Stock</label>
        <input type="number" name="stock" value="<?= $producto['stock'] ?>" required min="0">
    </div>

    <!-- IMAGEN + PREVIEW -->
    <div class="form-group">
        <label>Nombre del archivo de imagen</label>
        <input 
            type="text" 
            name="imagen" 
            id="imagen_input"
            value="<?= htmlspecialchars($producto['imagen']) ?>"
            oninput="previewImage()"
            required
        >
    </div>

    <!-- PREVIEW - ACTUAL + CAMBIOS -->
    <div class="img-preview-box">
        <img 
            id="img_preview"
            src="/mi_tienda/img/<?= htmlspecialchars($producto['imagen']) ?>"
            style="display:block;"
        >
    </div>

    <div class="form-group">
        <label>Categoría</label>
        <select name="categoria" required>
            <?php foreach ($categorias as $c): ?>
                <option 
                    value="<?= $c ?>" 
                    <?= $producto["categoria"] === $c ? "selected" : "" ?>>
                    <?= ucfirst($c) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-actions">
        <a href="/mi_tienda/admin/index.php" class="btn-secundario">Volver</a>
        <button type="submit" class="btn-primario">Guardar cambios</button>
    </div>

</form>

</div>

<script>
function previewImage() {
    const fileName = document.getElementById("imagen_input").value.trim();
    const preview = document.getElementById("img_preview");

    if (fileName === "") {
        preview.src = "";
        preview.style.display = "none";
        return;
    }

    preview.src = "/mi_tienda/img/" + fileName;
    preview.style.display = "block";
}
</script>



</body>
</html>
