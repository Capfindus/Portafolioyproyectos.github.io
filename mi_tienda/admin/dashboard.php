<?php
include "../config/db.php";
session_start();

// Protección de acceso
if (!isset($_SESSION["admin"])) {
    header("Location: /mi_tienda/admin/login.php");
    exit;
}

// =================== CONSULTAS =================== //
$totalProd = $conexion->query("SELECT COUNT(*) AS total FROM productos")->fetch_assoc()['total'];
$stockTotal = $conexion->query("SELECT SUM(stock) AS total FROM productos")->fetch_assoc()['total'];

// Stock por producto (para gráficos)
$names = [];
$stocks = [];
$res = $conexion->query("SELECT nombre, stock FROM productos ORDER BY stock ASC");
while ($row = $res->fetch_assoc()) {
    $names[] = $row['nombre'];
    $stocks[] = $row['stock'];
}

// Productos con stock bajo (agotándose)
$lowStock = [];
$res2 = $conexion->query("SELECT nombre, stock FROM productos WHERE stock <= 5 ORDER BY stock ASC");
while ($row = $res2->fetch_assoc()) {
    $lowStock[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Admin</title>

    <link rel="stylesheet" href="/mi_tienda/admin/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* CONTENEDOR GENERAL */
        .dashboard-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .titulo {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #6c3cff;
        }

        /* CARDS DE RESUMEN */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .card {
            background: #fff;
            padding: 22px;
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
            border-left: 7px solid #6c3cff;
        }

        .card h3 {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 10px;
            color: #6c3cff;
        }

        .card-num {
            font-size: 36px;
            font-weight: 800;
            margin: 0;
            color: #2d2d2d;
        }

        /* CAJA DE GRAFICOS */
        .chart-box {
            background: #fff;
            padding: 25px;
            margin-bottom: 35px;
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        }

        .chart-title {
            font-size: 22px;
            margin-bottom: 18px;
            font-weight: 700;
            color: #6c3cff;
            text-align: center;
        }

        /* TABLA */
        .tabla {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
            margin-bottom: 40px;
        }

        .tabla th, .tabla td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 15px;
        }

        .tabla th {
            background: #f0eaff;
            font-weight: 700;
            color: #6c3cff;
        }

        .stock-critical {
            color: #d63030;
            font-weight: 700;
        }
    </style>
</head>

<body>

<!-- ========================= HEADER ADMIN ========================= -->
<header class="admin-header">
    <h1>Panel de Control</h1>
    <nav>
        <a href="/mi_tienda/admin/dashboard.php">Dashboard</a>
        <a href="/mi_tienda/admin/index.php">Productos</a>
        <a href="/mi_tienda/admin/crear.php">Crear Producto</a>
        <a href="/mi_tienda" target="_blank">Ver Tienda</a>
        <a href="/mi_tienda/admin/logout.php" class="logout">Cerrar sesión</a>
    </nav>
</header>


<!-- ========================= CONTENIDO ========================= -->
<div class="dashboard-container">

    <h1 class="titulo">Resumen General</h1>

    <!-- Tarjetas -->
    <div class="cards">
        <div class="card">
            <h3>Total de Productos</h3>
            <p class="card-num"><?= $totalProd ?></p>
        </div>

        <div class="card">
            <h3>Stock Total</h3>
            <p class="card-num"><?= $stockTotal ?></p>
        </div>

        <div class="card">
            <h3>Stock Bajo (≤ 5)</h3>
            <p class="card-num"><?= count($lowStock) ?></p>
        </div>
    </div>


    <!-- GRAFICO PIE -->
    <div class="chart-box">
        <h2 class="chart-title">Distribución de Stock por Producto</h2>
        <canvas id="pieChart" height="150"></canvas>
    </div>

    <!-- GRAFICO BARRAS -->
    <div class="chart-box">
        <h2 class="chart-title">Stock de Productos (Barras)</h2>
        <canvas id="barChart" height="150"></canvas>
    </div>

    <!-- TABLA DE STOCK BAJO -->
    <h2 class="chart-title">Productos con stock bajo</h2>
    
    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Stock</th>
            </tr>
        </thead>

        <tbody>
        <?php if (count($lowStock) === 0): ?>
            <tr>
                <td colspan="2" style="text-align:center; padding:25px;">No hay productos con poco stock 🎉</td>
            </tr>
        <?php else: ?>
            <?php foreach ($lowStock as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td class="<?= $p['stock'] <= 2 ? 'stock-critical' : '' ?>">
                    <?= $p['stock'] ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

</div>


<!-- ========================= SCRIPTS DE GRAFICOS ========================= -->
<script>
const labels = <?= json_encode($names) ?>;
const stocks = <?= json_encode($stocks) ?>;

// PIE
new Chart(document.getElementById("pieChart"), {
    type: "doughnut",
    data: {
        labels: labels,
        datasets: [{
            backgroundColor: [
                "#6c3cff", "#a78bfa", "#c4b5fd", "#7c3aed",
                "#9d4edd", "#b48cff"
            ],
            data: stocks
        }]
    }
});

// BARRAS
new Chart(document.getElementById("barChart"), {
    type: "bar",
    data: {
        labels: labels,
        datasets: [{
            label: "Stock",
            data: stocks,
            backgroundColor: "#6c3cff"
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
