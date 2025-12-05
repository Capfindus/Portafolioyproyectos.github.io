<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de compras</title>
    <link rel="stylesheet" href="/mi_tienda/style.css">

    <style>
        .carrito-container {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }

        .carrito-titulo {
            text-align: center;
            margin-bottom: 25px;
            font-size: 32px;
            font-weight: 700;
            color: #1f1f1f;
        }

        .carrito-vacio {
            text-align: center;
            font-size: 20px;
            padding: 40px;
            color: #444;
        }

        .carrito-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .carrito-img {
            width: 85px;
            height: 85px;
            border-radius: 10px;
            border: 1px solid #ddd;
            object-fit: contain;
            background: #f8f9fb;
        }

        .carrito-nombre {
            font-size: 18px;
            font-weight: 600;
            color: #222;
        }

        .carrito-precio {
            font-size: 17px;
            font-weight: 600;
            color: #2d8cf0;
        }

        .carrito-cantidad {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cant {
            background: #2d8cf0;
            border: none;
            color: #fff;
            padding: 5px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background .2s;
        }

        .btn-cant:hover {
            background: #1a6bce;
        }

        .btn-eliminar {
            background: #ff4d4d;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            transition: background .2s;
        }

        .btn-eliminar:hover {
            background: #d93636;
        }

        .carrito-total {
            text-align: right;
            font-size: 24px;
            font-weight: 700;
            margin-top: 25px;
        }

        .acciones-carrito {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn-volver,
        .btn-vaciar {
            display: inline-block;
            padding: 12px 18px;
            background: #2d8cf0;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background .2s;
        }

        .btn-volver:hover,
        .btn-vaciar:hover {
            background: #1a6bce;
        }

        .btn-vaciar {
            background: #ff4d4d !important;
        }

        .btn-vaciar:hover {
            background: #d93636 !important;
        }

        @media(max-width: 700px) {
            .carrito-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

</head>
<body>

<header class="main-header">
    <div class="header-container">
        <h1 class="logo">Minitienda</h1>

        <nav class="navbar">
            <ul>
                <li><a class="btn-nav" href="/mi_tienda/index.php">Inicio</a></li>
                <li><a class="btn-nav" href="/mi_tienda/index.php">Productos</a></li>
                <li><a class="btn-nav" href="/mi_tienda/pages/carrito.php">🛒 Carrito <span id="cartCount">0</span></a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="carrito-container">
    <h2 class="carrito-titulo">Carrito de compras</h2>

    <div id="carritoItems"></div>

    <p class="carrito-total" id="carritoTotal">Total: $0</p>

    <div class="acciones-carrito">
        <a href="/mi_tienda/index.php" class="btn-volver">⬅ Seguir comprando</a>
        <button class="btn-vaciar" id="btnVaciar">Vaciar carrito</button>
    </div>
</div>

<script>
// Mostrar carrito al cargar
document.addEventListener("DOMContentLoaded", () => {

    const contenedor = document.getElementById("carritoItems");
    const totalHTML = document.getElementById("carritoTotal");
    const btnVaciar = document.getElementById("btnVaciar");

    function actualizarContador() {
        const contador = document.getElementById("cartCount");
        let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
        contador.textContent = carrito.reduce((acc, p) => acc + p.cantidad, 0);
    }

    function cargarCarrito() {
        let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

        contenedor.innerHTML = "";

        if (carrito.length === 0) {
            contenedor.innerHTML = "<p class='carrito-vacio'>Tu carrito está vacío 🛒</p>";
            totalHTML.textContent = "Total: $0";
            actualizarContador();
            return;
        }

        let total = 0;

        carrito.forEach((p, index) => {
            let subtotal = p.precio * p.cantidad;
            total += subtotal;

            contenedor.innerHTML += `
                <div class="carrito-item">
                    <img src="${p.imagen}" class="carrito-img">

                    <div style="flex:1;">
                        <p class="carrito-nombre">${p.nombre}</p>
                        <p class="carrito-precio">$${p.precio.toLocaleString()}</p>
                    </div>

                    <div class="carrito-cantidad">
                        <button class="btn-cant" onclick="cambiarCantidad(${index}, -1)">-</button>
                        <span>${p.cantidad}</span>
                        <button class="btn-cant" onclick="cambiarCantidad(${index}, 1)">+</button>
                    </div>

                    <button class="btn-eliminar" onclick="eliminarProducto(${index})">Eliminar</button>
                </div>
            `;
        });

        totalHTML.textContent = "Total: $" + total.toLocaleString();
        actualizarContador();
    }

    // Cambiar cantidad
    window.cambiarCantidad = function(index, valor) {
        let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
        carrito[index].cantidad += valor;

        if (carrito[index].cantidad <= 0) {
            carrito.splice(index, 1);
        }

        localStorage.setItem("carrito", JSON.stringify(carrito));
        cargarCarrito();
    };

    // Eliminar producto
    window.eliminarProducto = function(index) {
        let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
        carrito.splice(index, 1);
        localStorage.setItem("carrito", JSON.stringify(carrito));
        cargarCarrito();
    };

    // Vaciar carrito
    btnVaciar.addEventListener("click", () => {
        localStorage.removeItem("carrito");
        cargarCarrito();
    });

    cargarCarrito();
});
</script>

</body>
</html>
