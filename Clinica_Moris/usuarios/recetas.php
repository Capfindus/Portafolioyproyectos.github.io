<?php
require "../config/seguridad.php";
require "../config/db.php";

$usuario_id = $_SESSION["usuario_id"];

$sql = "SELECT * FROM recetas WHERE usuario_id = $usuario_id ORDER BY fecha_receta DESC";
$result = $conn->query($sql);
?>

<?php include "layout.php"; ?>

<h2>Mis Recetas Médicas</h2>

<table>
    <tr>
        <th>Fecha</th>
        <th>Doctor</th>
        <th>Medicamentos</th>
        <th>Acciones</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row["fecha_receta"]; ?></td>
        <td><?php echo $row["doctor_nombre"]; ?></td>
        <td><?php echo nl2br($row["medicamentos"]); ?></td>
        <td>
            <a href="../uploads/examenes/<?php echo $usuario_id; ?>/<?php echo $row["archivo_pdf"]; ?>" download>
                Descargar Receta
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</div></div></body></html>
