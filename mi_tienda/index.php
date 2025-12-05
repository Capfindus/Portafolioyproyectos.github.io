<?php
include "config/db.php";

// Consulta ordenada por categoría
$sql = "SELECT * FROM productos ORDER BY categoria, nombre ASC";
$result = $conexion->query($sql);

// Organizar productos por categoría
$categorias = [
    "celulares" => [],
    "teclados" => [],
    "mouse" => [],
    "audifonos" => [],
    "accesorios" => [],
    "general" => []
];

while ($p = $result->fetch_assoc()) {
    $cat = strtolower($p["Categoria"]);
    if (isset($categorias[$cat])) {
        $categorias[$cat][] = $p;
    } else {
        $categorias["general"][] = $p;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Minitienda | Productos</title>

    <!-- SEO -->
    <meta name="description" content="Catálogo completo de productos de Minitienda: celulares, audífonos, teclados, accesorios y más.">

    <!-- Mobile Config -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/mi_tienda/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" >

</head>
<body>

<!-- ===============================
            HEADER
================================= -->
<header class="main-header">
    <div class="header-container">

        <h1 class="logo">Minitienda</h1>

        <nav class="navbar" aria-label="Menú principal">
            <ul>
                <li><a class="btn-nav" href="/mi_tienda/index.php">Inicio</a></li>
                <li><a class="btn-nav" href="/mi_tienda/pages/producto.php">Productos</a></li>
                <li><a class="admin-btn" href="/mi_tienda/admin/login.php">Iniciar Sesión</a></li>
            </ul>
        </nav>
        
        <!-- Menú móvil -->
        <div class="nav-toggle" id="navToggle">
            ☰
        </div>

    </div>
</header>

<div class="layout-tienda">

<!-- ===============================
       PANEL IZQUIERDO DE FILTROS
================================= -->
<aside class="panel-filtros" aria-label="Filtros de productos">

    <h3 class="titulo-filtro">Categorías</h3>

    <div class="filtros-categorias">
        <button class="filtro-btn activo" data-cat="todos">Todos</button>
        <button class="filtro-btn" data-cat="celulares">Celulares</button>
        <button class="filtro-btn" data-cat="teclados">Teclados</button>
        <button class="filtro-btn" data-cat="mouse">Mouse</button>
        <button class="filtro-btn" data-cat="audifonos">Audífonos</button>
        <button class="filtro-btn" data-cat="accesorios">Accesorios</button>
    </div>

    <h3 class="titulo-filtro">Precio</h3>

    <div class="filtros-precio">
        <label><input type="radio" name="precio" value="0-20000"> Hasta $20.000</label>
        <label><input type="radio" name="precio" value="20000-50000"> $20.000 - $50.000</label>
        <label><input type="radio" name="precio" value="50000-100000"> $50.000 - $100.000</label>
        <label><input type="radio" name="precio" value="100000-9999999"> Más de $100.000</label>
        <label><input type="radio" name="precio" value="todos" checked> Cualquier precio</label>
    </div>

</aside>

<!-- ===============================
          ÁREA DE PRODUCTOS
================================= -->
<main class="productos-area" aria-live="polite">

    <?php
    function mostrarCategoria($nombre, $productos) {
        if (count($productos) == 0) return;

        echo "<section class='categoria-section' data-categoria='$nombre'>";
        echo "<h2 class='categoria-titulo'>" . ucfirst($nombre) . "</h2>";
        echo "<div class='catalogo'>";

        foreach ($productos as $p) {

            $precio = (int)$p['precio'];
            $categoriaJS = strtolower($p['Categoria']);

            echo "
            <a href='/mi_tienda/pages/producto.php?id={$p['id']}'
               class='producto-card'
               data-cat='{$categoriaJS}'
               data-precio='{$precio}'
               aria-label='Ver detalles de {$p['nombre']}'>
               
                <div class='card-img-box'>
                    <img src='/mi_tienda/img/{$p['imagen']}' alt='Imagen de {$p['nombre']}'>
                </div>

                <h3 class='card-title'>{$p['nombre']}</h3>
                <p class='card-desc'>{$p['descripcion']}</p>

                <p class='card-precio'>$" . number_format($precio, 0, ",", ".") . "</p>
                <p class='card-stock'>Stock: {$p['stock']}</p>

                <button 
                    class='btn-add-cart' 
                    data-id='{$p['id']}'
                    data-nombre='{$p['nombre']}'
                    data-precio='{$precio}'
                    data-imagen='/mi_tienda/img/{$p['imagen']}'>
                    Agregar al carrito
                </button>

            </a>";
        }
        

        echo "</div></section>";
    }
    ?>

    <?php mostrarCategoria("celulares", $categorias["celulares"]); ?>
    <?php mostrarCategoria("teclados", $categorias["teclados"]); ?>
    <?php mostrarCategoria("mouse", $categorias["mouse"]); ?>
    <?php mostrarCategoria("audifonos", $categorias["audifonos"]); ?>
    <?php mostrarCategoria("accesorios", $categorias["accesorios"]); ?>
    <?php mostrarCategoria("general", $categorias["general"]); ?>

</main>

</div> <!-- fin layout-tienda -->

<!-- ===============================
             FOOTER
================================= -->
<footer class="tienda-footer">
    © 2025 Minitienda – Desarrollado por Matías Fuentes
</footer>

<!-- ===============================
        SCRIPT DE FILTRO
================================= -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    const botones = document.querySelectorAll(".filtro-btn");
    const radiosPrecio = document.querySelectorAll("input[name='precio']");
    const productos = document.querySelectorAll(".producto-card");

    let categoria = "todos";
    let precio = "todos";

    function filtrar() {
        productos.forEach(card => {
            let cat = card.dataset.cat;
            let valor = parseInt(card.dataset.precio);

            let coincideCat = (categoria === "todos" || categoria === cat);

            let coincidePrecio = true;
            if (precio !== "todos") {
                const [min, max] = precio.split("-").map(Number);
                coincidePrecio = (valor >= min && valor <= max);
            }

            card.classList.toggle("oculto", !(coincideCat && coincidePrecio));
        });
    }

    botones.forEach(btn => {
        btn.addEventListener("click", () => {
            botones.forEach(b => b.classList.remove("activo"));
            btn.classList.add("activo");
            categoria = btn.dataset.cat;
            filtrar();
        });
    });

    radiosPrecio.forEach(r => {
        r.addEventListener("change", () => {
            precio = r.value;
            filtrar();
        });
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    // BOTONES
    const botonesCarrito = document.querySelectorAll('.btn-add-cart');

    // EVENTO: agregar producto
    botonesCarrito.forEach(boton => {
        boton.addEventListener('click', () => {

            const id = boton.dataset.id;
            const nombre = boton.dataset.nombre;
            const precio = parseInt(boton.dataset.precio);
            const imagen = boton.dataset.imagen;

            let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

            // Si ya existe, aumentar cantidad
            const existente = carrito.find(p => p.id === id);
            if (existente) {
                existente.cantidad++;
            } else {
                carrito.push({
                    id,
                    nombre,
                    precio,
                    imagen,
                    cantidad: 1
                });
            }

            localStorage.setItem("carrito", JSON.stringify(carrito));

            actualizarContadorCarrito();

            // Animación visual
            boton.textContent = "✓ Agregado";
            setTimeout(() => boton.textContent = "Agregar al carrito", 1000);

        });
    });

    // Mostrar número de items en el header
    function actualizarContadorCarrito() {
        const contador = document.getElementById("cartCount");
        if (!contador) return;

        let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
        let total = carrito.reduce((acc, p) => acc + p.cantidad, 0);

        contador.textContent = total;
    }

    actualizarContadorCarrito();
});
</script>

</body>
</html>
