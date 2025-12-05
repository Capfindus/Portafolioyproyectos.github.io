<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Minitienda</title>
    <link rel="stylesheet" href="/mi_tienda/style.css">
</head>
<body>

<header class="main-header">
    <div class="header-container">

        <h1 class="logo">Minitienda</h1>

        <!-- Botón para móvil -->
        <div class="nav-toggle" id="navToggle">
            ☰
        </div>

        <nav class="navbar">
        <ul>
            <li><a class="nav-btn" href="/mi_tienda/index.php">Inicio</a></li>
            <li><a class="nav-btn" href="/mi_tienda/pages/index.php">Productos</a></li>
            <li><a class="nav-btn admin" href="/mi_tienda/admin/login.php">Admin</a></li>
            <li>
            <a class="btn-nav" href="/mi_tienda/pages/carrito.php">
                🛒 Carrito <span id="cartCount">0</span>
            </a>
            </li>

        </ul>
        </nav>
    </div>
</header>

<main>
