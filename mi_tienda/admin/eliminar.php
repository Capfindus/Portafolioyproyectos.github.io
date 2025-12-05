<?php
include "../config/db.php";
session_start();

// Protección
if (!isset($_SESSION["admin"])) {
    header("Location: /mi_tienda/admin/login.php");
    exit;
}

// Validar ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];

// Buscar producto
$sql = $conexion->query("SELECT nombre, imagen FROM productos WHERE id = $id");
if ($sql->num_rows === 0) {
    header("Location: index.php?error=notfound");
    exit;
}

$producto = $sql->fetch_assoc();
$nombreProducto = $producto['nombre'];
$archivoImagen = $producto['imagen'];

// Si el usuario confirma la eliminación
if (isset($_POST['confirmar'])) {

    // Eliminar registro
    $conexion->query("DELETE FROM productos WHERE id = $id");

    // Eliminar imagen física (si existe)
    $ruta = "../img/" . $archivoImagen;
    if (file_exists($ruta)) {
        unlink($ruta);
    }

    header("Location: index.php?deleted=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar | Admin</title>
    <link rel="stylesheet" href="/mi_tienda/admin/admin.css">
</head>
<body>

<!-- HEADER -->
<header class="admin-header">
    <h1>Eliminar producto</h1>
    <nav>
        <a href="/mi_tienda/admin/dashboard.php">Dashboard</a>
        <a href="/mi_tienda/admin/index.php">Productos</a>
        <a href="/mi_tienda/admin/logout.php" class="logout">Cerrar sesión</a>
    </nav>
</header>

<div class="form-card">
    <h1>Confirmar eliminación</h1>

    <p style="font-size: 17px; text-align:center;">
        ¿Deseas eliminar el producto:<br><br>
        <strong style="font-size:20px; color:#6c3cff;">
            <?= htmlspecialchars($nombreProducto) ?>
        </strong> ?
    </p>

    <div class="img-preview-box">
        <img src="/mi_tienda/img/<?= htmlspecialchars($archivoImagen) ?>"
             style="max-width:160px; max-height:160px;">
    </div>

    <form method="POST" class="form-actions" style="justify-content:center; margin-top:20px;">
        <a href="/mi_tienda/admin/index.php" class="btn-secundario">Cancelar</a>

        <button 
            type="submit" 
            name="confirmar" 
            class="btn-primario" 
            style="background:#ff4d4d;">
            Eliminar
        </button>
    </form>
</div>

</body>
</html>
