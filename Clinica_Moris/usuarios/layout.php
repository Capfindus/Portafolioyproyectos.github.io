<?php
require "../config/seguridad.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clinica Moris</title>

    <!-- CSS GLOBAL CORRECTO -->
    <link rel="stylesheet" href="/Clinica_Moris/assets/css/style.css?v=4">

    <!-- ICONOS PROFESIONALES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Clinica Moris</h2>

        <a href="index.php"><i class="bi bi-house"></i> Inicio</a>
        <a href="perfil.php"><i class="bi bi-person-circle"></i> Mi Perfil</a>
        <a href="examenes.php"><i class="bi bi-beaker"></i> Mis Exámenes</a>
        <a href="recetas.php"><i class="bi bi-prescription"></i> Mis Recetas</a>
        <a href="reservar_hora.php"><i class="bi bi-calendar"></i> Reservar Hora</a>
        <a href="boletas.php"><i class="bi bi-receipt"></i> Mis Boletas</a>
        <a href="notificaciones.php"><i class="bi bi-bell"></i> Notificaciones</a>
        <a href="cambiar_contrasena.php"><i class="bi bi-lock"></i> Cambiar Contraseña</a>
        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
    </div>

    <div class="content">
