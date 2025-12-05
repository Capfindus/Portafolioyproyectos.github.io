<?php
$host = "localhost";
$user = "root";
$password = ""; // cámbialo si tienes clave
$db_name = "clinica_moris";

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
