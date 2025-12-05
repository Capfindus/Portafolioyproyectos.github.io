<?php
require "../config/seguridad.php";
require "../config/db.php";

$usuario_id = $_SESSION["usuario_id"];

$boletas = $conn->query("
    SELECT * FROM boletas 
    WHERE usuario_id = $usuario_id 
    ORDER BY fecha DESC
");
?>

<?php include "layout.php"; ?>

<h2>Mis Boletas</h2>

<table>
    <tr>
        <th>N° Boleta</th>
        <th>Fecha</th>
        <th>Monto</th>
        <th>Detalle</th>
        <th>Descargar</th>
    </tr>

    <?php while ($b = $boletas->fetch_assoc()): ?>
    <tr>
        <td><?php echo $b["numero_boleta"]; ?></td>
        <td><?php echo $b["fecha"]; ?></td>
        <td>$<?php echo number_format($b["monto"], 0, ",", ".");
            ?></td>
        <td><?php echo $b["detalle"]; ?></td>
        <td>
            <a href="../uploads/boletas/<?php echo $usuario_id; ?>/<?php echo $b["archivo_pdf"]; ?>" download>
                Descargar PDF
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</div></div></body></html>
