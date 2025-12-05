<?php
require "../config/seguridad.php";
require "../config/db.php";

$usuario_id = $_SESSION["usuario_id"];

$sql = "SELECT * FROM examenes WHERE usuario_id = $usuario_id ORDER BY fecha_examen DESC";
$result = $conn->query($sql);
?>

<?php include "layout.php"; ?>

<h2>Mis Exámenes</h2>

<table>
    <tr>
        <th>Tipo</th>
        <th>Fecha</th>
        <th>Descripción</th>
        <th>Acciones</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row["tipo"]; ?></td>
        <td><?php echo $row["fecha_examen"]; ?></td>
        <td><?php echo $row["descripcion"]; ?></td>
        <td>
            <a href="../uploads/examenes/<?php echo $usuario_id; ?>/<?php echo $row["archivo_pdf"]; ?>" download>
                Descargar
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</div></div></body></html>
