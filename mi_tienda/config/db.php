<?php
$conexion = new mysqli("localhost", "root", "", "minitienda");

if($conexion->connect_error){
    die("Error de conexion: ". $conexion->connect_error);
}
?>