<?php 
include "../config/db.php";
include "../partials/header.php";

// Validar ID
if (!isset($_GET['id'])) {
    echo "<p class='error-msj'>Producto no especificado.</p>";
    include "../partials/footer.php";
    exit;
}

$id = intval($_GET['id']);

// Consultar producto
$query = $conexion->query("SELECT * FROM productos WHERE id = $id");

if ($query->num_rows == 0) {
    echo "<p class='error-msj'>Producto no encontrado.</p>";
    include "../partials/footer.php";
    exit;
}

$producto = $query->fetch_assoc();
?>

<main class="producto-detalle">

    <div class="detalle-container">

        <!-- IMAGEN -->
        <div class="detalle-img-box">
            <img src="/mi_tienda/img/<?php echo $producto['imagen']; ?>" 
                 alt="<?php echo $producto['nombre']; ?>" 
                 class="detalle-img">
        </div>

        <!-- INFORMACIÓN -->
        <div class="detalle-info">
            <h1><?php echo $producto['nombre']; ?></h1>

            <p class="detalle-descripcion">
                <?php echo $producto['descripcion']; ?>
            </p>

            <p class="detalle-precio">
                $<?php echo number_format($producto['precio'], 0, ",", "."); ?>
            </p>

            <p class="detalle-stock">
                Stock disponible: <?php echo $producto['stock']; ?>
            </p>

            <a href="/mi_tienda/index.php" class="btn-volver">← Volver a productos</a>
        </div>

    </div>

</main>

<?php include "../partials/footer.php"; ?>

</body>
</html>
