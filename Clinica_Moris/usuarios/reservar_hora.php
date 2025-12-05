<?php
require "../config/seguridad.php";
require "../config/db.php";
require "../config/correo.php";
require "../config/notificaciones.php";
require "../librerias/dompdf/autoload.inc.php";

use Dompdf\Dompdf;

$usuario_id = $_SESSION["usuario_id"];
$mensaje = "";

// Registrar nueva hora
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $especialidad_id = $_POST["especialidad"];
    $doctor_id = $_POST["doctor"];
    $fecha = $_POST["fecha"];
    $hora = $_POST["hora"];

    $sql = "INSERT INTO horas_medicas (usuario_id, doctor_id, fecha, hora)
            VALUES ($usuario_id, $doctor_id, '$fecha', '$hora')";

    if ($conn->query($sql)) {

        // ======== DATOS USUARIO Y DOCTOR =========
        $user = $conn->query("SELECT * FROM usuarios WHERE id=$usuario_id")->fetch_assoc();
        $doctorInfo = $conn->query("SELECT nombre FROM doctores WHERE id=$doctor_id")->fetch_assoc();

        // ======== GENERAR BOLETA =========
        $numero_boleta = "BM-" . rand(100000, 999999);
        $fecha_boleta = date("Y-m-d");
        $monto = 12000; // puedes cambiarlo
        $detalle = "Reserva de hora con el doctor " . $doctorInfo["nombre"] . " el día $fecha a las $hora.";
        $nombre_pdf = "boleta_" . $numero_boleta . ".pdf";

        $html = '
        <style>
            body { font-family: Arial; }
            .box { border: 1px solid #ccc; padding: 20px; border-radius: 10px; }
            .title { font-size: 22px; margin-bottom: 10px; }
        </style>

        <div class="box">
            <h2 class="title">Clínica Moris</h2>
            <p><strong>Número de Boleta:</strong> ' . $numero_boleta . '</p>
            <p><strong>Fecha:</strong> ' . $fecha_boleta . '</p>

            <hr>

            <h3>Datos del Paciente</h3>
            <p><strong>Nombre:</strong> ' . $user["nombre"] . ' ' . $user["apellido"] . '</p>
            <p><strong>RUT:</strong> ' . $user["rut"] . '</p>
            <p><strong>Email:</strong> ' . $user["email"] . '</p>

            <hr>

            <h3>Detalle del Servicio</h3>
            <p>' . nl2br($detalle) . '</p>

            <p><strong>Monto:</strong> $' . number_format($monto, 0, ',', '.') . '</p>
        </div>
        ';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper("A4", "portrait");
        $dompdf->render();

        $path = "../uploads/boletas/" . $usuario_id;
        if (!is_dir($path)) mkdir($path, 0777, true);

        file_put_contents("$path/$nombre_pdf", $dompdf->output());

        $conn->query("
            INSERT INTO boletas (usuario_id, numero_boleta, fecha, monto, detalle, archivo_pdf)
            VALUES ($usuario_id, '$numero_boleta', '$fecha_boleta', $monto, '$detalle', '$nombre_pdf')
        ");

        // ======== NOTIFICACIÓN INTERNA =========
        function crearNotificacion($usuario_id, $mensaje) {
            global $conn;
            $conn->query("INSERT INTO notificaciones (usuario_id, mensaje) VALUES ($usuario_id, '$mensaje')");
        }

        crearNotificacion(
            $usuario_id,
            "Hora reservada",
            "Has reservado una hora con el doctor " . $doctorInfo["nombre"] . " para el día $fecha a las $hora."
        );

        // ======== CORREO =========
        $correoHTML = "
        <h2>Clinica Moris</h2>
        <p>Estimado/a {$user['nombre']} {$user['apellido']}:</p>
        <p>Tu hora ha sido reservada exitosamente.</p>
        <p><strong>Doctor:</strong> {$doctorInfo['nombre']}<br>
           <strong>Fecha:</strong> $fecha<br>
           <strong>Hora:</strong> $hora</p>
        <p>Podrás descargar tu boleta desde el portal de usuario.</p>
        ";

        enviarCorreo($user["email"], "Confirmación de hora reservada", $correoHTML);

        $mensaje = "Hora reservada correctamente, boleta generada y correo enviado.";

    } else {
        $mensaje = "Error al reservar: " . $conn->error;
    }
}

// Obtener especialidades
$especialidades = $conn->query("SELECT * FROM especialidades ORDER BY nombre ASC");

// Obtener doctores
$doctores = $conn->query("SELECT * FROM doctores ORDER BY nombre ASC");

// Obtener horas reservadas del usuario
$horas = $conn->query("
    SELECT h.*, d.nombre AS doctor_nombre
    FROM horas_medicas h
    LEFT JOIN doctores d ON d.id = h.doctor_id
    WHERE usuario_id = $usuario_id
    ORDER BY fecha DESC, hora DESC
");
?>

<?php include "layout.php"; ?>

<h2>Reserva de Horas Médicas</h2>

<p><?php echo $mensaje; ?></p>

<h3>Reservar nueva hora</h3>

<form method="POST">

    <label>Especialidad:</label><br>
    <select name="especialidad" id="especialidad" required>
        <option value="">Seleccione...</option>
        <?php while ($esp = $especialidades->fetch_assoc()): ?>
        <option value="<?php echo $esp["id"]; ?>"><?php echo $esp["nombre"]; ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Doctor:</label><br>
    <select name="doctor" id="doctor" required>
        <option value="">Seleccione una especialidad primero...</option>
        <?php while ($doc = $doctores->fetch_assoc()): ?>
        <option value="<?php echo $doc["id"]; ?>" data-esp="<?php echo $doc["especialidad_id"]; ?>">
            <?php echo $doc["nombre"]; ?>
        </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Fecha:</label><br>
    <input type="date" name="fecha" required><br><br>

    <label>Hora:</label><br>
    <input type="time" name="hora" required><br><br>

    <button type="submit">Reservar</button>
</form>

<hr>

<h3>Mis horas reservadas</h3>

<table>
    <tr>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Doctor</th>
        <th>Estado</th>
    </tr>

    <?php while ($h = $horas->fetch_assoc()): ?>
    <tr>
        <td><?php echo $h["fecha"]; ?></td>
        <td><?php echo $h["hora"]; ?></td>
        <td><?php echo $h["doctor_nombre"] ?? 'N/A'; ?></td>
        <td>
            <?php echo $h["estado"]; ?>
            <?php if ($h["estado"] === "reservada" && $h["fecha"] >= date("Y-m-d")): ?>
                | <a href="cancelar_hora.php?id=<?php echo $h["id"]; ?>"
                     onclick="return confirm('¿Cancelar esta hora?');">Cancelar</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<script>
document.getElementById("especialidad").addEventListener("change", function () {
    let esp = this.value;
    let doctores = document.querySelectorAll("#doctor option");

    doctores.forEach(opt => {
        if (!opt.value) return;
        opt.style.display = (opt.dataset.esp === esp) ? "block" : "none";
    });

    document.getElementById("doctor").value = "";
});
</script>

</div></div></body></html>
