<?php
require "correo.php"; // usa PHPMailer configurado

$nombre = $_POST["nombre"];
$email = $_POST["email"];
$mensaje = $_POST["mensaje"];

$asunto = "Nuevo mensaje desde el formulario de contacto";
$contenido = "
    Nombre: $nombre<br>
    Correo: $email<br><br>
    Mensaje:<br>$mensaje
";

enviarCorreo("contacto@clinicamoris.cl", $asunto, $contenido); // Reemplaza con tu correo real

header("Location: ../index.php?msg=ok");
exit;
?>
