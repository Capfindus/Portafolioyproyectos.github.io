<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si NO hay usuario -> redirigir al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
