<?php
require "../config/seguridad.php";
require "../config/db.php";
require "../librerias/dompdf/autoload.inc.php";

use Dompdf\Dompdf;

$usuario_id = $_SESSION["usuario_id"];

// Datos del usuario
$sql_user = "SELECT * FROM usuarios WHERE id = $usuario_id LIMIT 1";
$user = $conn->query($sql_user)->fetch_assoc();

// Número aleatorio de boleta
$numero_boleta = "BM-" . rand(100000, 999999);

// Datos del formulario
$monto = $_POST["monto"];
$detalle = $_POST["detalle"];

$fecha = date("Y-m-d");

// Nombre del archivo
$nombre_pdf = "boleta_" . $numero_boleta . ".pdf";

// HTML del PDF (DISEÑO PROFESIONAL)
$html = '
<style>
body { font-family: Arial; }
.box { border: 1px solid #ccc; padding: 20px; border-radius: 10px; }
.title { font-size: 22px; margin-bottom: 10px; }
</style>

<div class="box">
    <h2 class="title">Clínica Moris</h2>
    <p><strong>Número de Boleta:</strong> ' . $numero_boleta . '</p>
    <p><strong>Fecha:</strong> ' . $fecha . '</p>

    <hr>

    <h3>Datos del Paciente</h3>
    <p><strong>Nombre:</strong> ' . $user["nombre"] . " " . $user["apellido"] . '</p>
    <p><strong>RUT:</strong> ' . $user["rut"] . '</p>
    <p><strong>Email:</strong> ' . $user["email"] . '</p>

    <hr>

    <h3>Detalle</h3>
    <p>' . nl2br($detalle) . '</p>

    <p><strong>Monto:</strong> $' . number_format($monto, 0, ',', '.') . '</p>
</div>
';

// Crear PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();

// Guardar en archivo
$output = $dompdf->output();
$path = "../uploads/boletas/" . $usuario_id;

if (!is_dir($path)) {
    mkdir($path, 0777, true);
}

file_put_contents("$path/$nombre_pdf", $output);

// GUARDAR EN BD
$conn->query("
    INSERT INTO boletas (usuario_id, numero_boleta, fecha, monto, detalle, archivo_pdf)
    VALUES ($usuario_id, '$numero_boleta', '$fecha', $monto, '$detalle', '$nombre_pdf')
");

header("Location: boletas.php");
exit;
?>
