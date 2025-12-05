<?php
include "../config/db.php";
session_start();

// Protección
if (!isset($_SESSION["admin"])) {
    header("Location: /mi_tienda/admin/login.php");
    exit;
}

$result = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos | Admin</title>
    <link rel="stylesheet" href="/mi_tienda/admin/admin.css">
    <style>
        /* --- Estilos adicionales para tabla moderna --- */
        .tabla-admin {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.10);
        }

        .tabla-admin th {
            background: #6c3cff;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        .tabla-admin td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .tabla-admin tr:hover {
            background: #f1ecff;
        }

        .btn-editar {
            background: #4CAF50;
            color: white;
        }
        .btn-eliminar {
            background: #ff4b4b;
            color: white;
        }

        .btn-accion {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            margin-right: 5px;
            display: inline-block;
        }

        /* mini imagen */
        .mini-img {
            width: 45px;
            height: 45px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fafafa;
            padding: 3px;
        }

        /* Stock colores */
        .stock-alto { color: #0d8a31; font-weight: bold; }
        .stock-medio { color: #b08500; font-weight: bold; }
        .stock-bajo { color: #d10000; font-weight: bold; }

        .admin-top-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            margin-bottom: 15px;
            align-items: center;
        }

        /* Buscador */
        .admin-search {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: right;
        }
        .admin-search input {
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 260px;
        }

        /* Mensajes */
        .flash-ok {
            padding: 10px;
            background: #d4ffe0;
            color: #1a7f3c;
            border-left: 5px solid #1a7f3c;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        .flash-error {
            padding: 10px;
            background: #ffe0e0;
            color: #b30000;
            border-left: 5px solid #b30000;
            margin-bottom: 15px;
            border-radius: 6px;
        }
    </style>

    <script>
        // Buscador en tiempo real
        function filtrarTabla() {
            const texto = document.getElementById("buscador").value.toLowerCase();
            const filas = document.querySelectorAll(".fila-prod");

            filas.forEach(fila => {
                const nombre = fila.querySelector(".col-nombre").textContent.toLowerCase();
                const categoria = fila.querySelector(".col-cat").textContent.toLowerCase();

                if (nombre.includes(texto) || categoria.includes(texto)) {
                    fila.style.display = "";
                } else {
                    fila.style.display = "none";
                }
            });
        }
    </script>
</head>
<body>

<header class="admin-header">
    <h1>Gestión de productos</h1>
    <nav>
        <a href="/mi_tienda/admin/dashboard.php">Dashboard</a>
        <a href="/mi_tienda/admin/index.php">Productos</a>
        <a href="/mi_tienda/admin/crear.php">Crear producto</a>
        <a href="/mi_tienda" target="_blank">Ver tienda</a>
        <a href="/mi_tienda/admin/logout.php" class="logout">Cerrar sesión</a>
    </nav>
</header>

<div class="admin-page">
    <h1>Listado de productos</h1>

    <!-- Mensajes Flash -->
    <?php if (isset($_GET['deleted'])): ?>
        <div class="flash-ok">Producto eliminado correctamente.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="flash-error">Hubo un error con la operación.</div>
    <?php endif; ?>

    <div class="admin-top-actions">
        <span>Total: <?= $result->num_rows ?> productos</span>
        <a href="crear.php" class="btn-primario">+ Nuevo producto</a>
    </div>

    <!-- Buscador -->
    <div class="admin-search">
        <input type="text" id="buscador" placeholder="Buscar por nombre o categoría..." onkeyup="filtrarTabla()">
    </div>

    <table class="tabla-admin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th style="width: 160px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($p = $result->fetch_assoc()): ?>

            <?php
                if ($p['stock'] <= 5) $stockClass = "stock-bajo";
                elseif ($p['stock'] <= 15) $stockClass = "stock-medio";
                else $stockClass = "stock-alto";
            ?>

            <tr class="fila-prod">
                <td><?= $p['id'] ?></td>

                <td>
                    <img src="/mi_tienda/img/<?= $p['imagen'] ?>" class="mini-img">
                </td>

                <td class="col-nombre"><?= htmlspecialchars($p['nombre']) ?></td>

                <td class="col-cat"><?= htmlspecialchars($p['categoria']) ?></td>

                <td>$<?= number_format($p['precio'], 0, ",", ".") ?></td>

                <td class="<?= $stockClass ?>"><?= $p['stock'] ?></td>

                <td>
                    <a href="editar.php?id=<?= $p['id'] ?>" class="btn-accion btn-editar">Editar</a>
                    <a href="eliminar.php?id=<?= $p['id'] ?>" class="btn-accion btn-eliminar">
                       Eliminar
                    </a>
                </td>
            </tr>

        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
